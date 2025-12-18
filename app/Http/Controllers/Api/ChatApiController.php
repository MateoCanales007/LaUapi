<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Chatify\Facades\ChatifyMessenger as Chatify;

class ChatApiController extends Controller
{

    /**
     * Autenticar la conexión de Pusher para canales privados
     */
    public function pusherAuth(Request $request)
    {
        $user = Auth::user();

        // 1. Validar datos
        if (!$request->channel_name || !$request->socket_id) {
            return response()->json(['message' => 'Faltan datos (channel_name o socket_id)'], 400);
        }

        // 2. Seguridad: Verificar que el usuario entra a SU canal
        // El canal llega como 'private-chatify.2' -> Extraemos el '2'
        $channelId = str_replace('private-chatify.', '', $request->channel_name);

        if ((string)$channelId !== (string)$user->id) {
            return response()->json(['message' => 'Unauthorized: ID no coincide'], 403);
        }

        try {
            // 3. Instanciar Pusher manualmente (Más seguro que usar la fachada de Chatify)
            $pusher = new Pusher(
                config('chatify.pusher.key'),
                config('chatify.pusher.secret'),
                config('chatify.pusher.app_id'),
                config('chatify.pusher.options')
            );

            // 4. Generar la firma de autenticación (Devuelve un JSON string)
            $auth = $pusher->socket_auth($request->channel_name, $request->socket_id);

            // Devolver directamente la cadena JSON
            return response($auth, 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Si falla, devolvemos el error REAL para que lo veas en la consola
            return response()->json([
                'message' => 'Error interno Pusher',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function getContactsJSON(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json([], 401);

        // 1. Obtener IDs de usuarios que son seguidores mutuos
        // (Usuarios que yo sigo Y que me siguen a mí)
        $mutualUsers = User::where('id', '!=', $user->id)
            ->whereIn('id', function ($query) use ($user) {
                // IDs de las personas que YO sigo
                $query->select('user_id')
                    ->from('followers')
                    ->where('follower_id', $user->id);
            })
            ->whereIn('id', function ($query) use ($user) {
                // IDs de las personas que ME siguen
                $query->select('follower_id')
                    ->from('followers')
                    ->where('user_id', $user->id);
            })
            ->get();

        // 2. Formatear la lista y agregar info de último mensaje (si existe)
        $contacts = $mutualUsers->map(function ($contact) use ($user) {

            // Buscar si ya hay historial de chat
            $lastMessage = ChMessage::where(function ($q) use ($user, $contact) {
                $q->where('from_id', $user->id)->where('to_id', $contact->id);
            })
                ->orWhere(function ($q) use ($user, $contact) {
                    $q->where('from_id', $contact->id)->where('to_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            $unreadCount = ChMessage::where('from_id', $contact->id)
                ->where('to_id', $user->id)
                ->where('seen', 0)
                ->count();

            return [
                'id' => $contact->id,
                'name' => $contact->name ?? $contact->username,
                'avatar' => $contact->imagen ? asset('perfiles/' . $contact->imagen) : asset('img/img.jpg'), // Asegura ruta completa
                'last_message' => $lastMessage ? $lastMessage->body : 'Toca para chatear',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : null,
                'last_message_date' => $lastMessage ? $lastMessage->created_at : null, // Para ordenar
                'unread_count' => $unreadCount,
            ];
        });

        // 3. Ordenar: Primero los que tienen mensajes recientes, luego los nuevos
        $sortedContacts = $contacts->sortByDesc(function ($contact) {
            return $contact['last_message_date'] ?? '1970-01-01';
        })->values();

        return response()->json($sortedContacts);
    }

    public function fetchMessagesJSON(Request $request)
    {
        $auth_id = Auth::id();
        $user_id = $request->id;

        if (!$auth_id) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $messages = ChMessage::where(function ($q) use ($auth_id, $user_id) {
            $q->where('from_id', $auth_id)->where('to_id', $user_id);
        })
            ->orWhere(function ($q) use ($auth_id, $user_id) {
                $q->where('from_id', $user_id)->where('to_id', $auth_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessageJSON(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'id' => 'required',
            'message' => 'required'
        ]);

        $message = new ChMessage();
        $message->id = Str::uuid();
        $message->from_id = $user->id;
        $message->to_id = $request->id;
        $message->body = $request->message;
        $message->attachment = null;
        $message->seen = 0;
        $message->created_at = now();
        $message->updated_at = now();
        $message->save();

        // --- AQUÍ ESTÁ LA MAGIA DEL TIEMPO REAL ---

        // Preparamos los datos para enviar por el socket
        // Chatify usa el canal 'private-chatify.{id_usuario}'
        $channel = 'private-chatify.' . $request->id;
        $event = 'messaging';

        $data = [
            'from_id' => $user->id,
            'to_id' => $request->id,
            'body' => $message->body, // Enviamos el texto puro
            'id' => $message->id,
            'created_at' => $message->created_at->toISOString(),
            'seen' => 0
        ];

        // Usamos la función nativa de Chatify para empujar a Pusher
        Chatify::push($channel, $event, $data);

        return response()->json($message);
    }

    // Nuevo método para manejar el evento "Escribiendo..."
    public function typing(Request $request)
    {
        $user = Auth::user();

        // Chatify usa el canal del RECEPTOR para notificaciones
        $channel = 'private-chatify.' . $request->id;

        // Evento 'client-typing'
        Chatify::push($channel, 'client-typing', [
            'from_id' => $user->id,
            'typing' => true
        ]);

        return response()->json(['status' => 'ok']);
    }
}

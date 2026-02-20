<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\su_ad;
use App\Models\User;
use App\Models\Banner;
use App\Models\Insignia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SUController extends Controller
{   
    use ValidatesRequests;

    public function login()
    {
      return view('su.login');   
    }

    public function storelau(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1️⃣ Verificar credenciales en superusuarios
        if (Auth::guard('super')->attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->route('su.dash');
        }

        // 3️⃣ Si nada coincide → error
        return back()
            ->with('status', 'Las credenciales no coinciden')
            ->withInput($request->only('email'));
    }

    public function storeus()
    {
        if (Auth::guard('super')->check()) {
            Auth::guard('super')->logout(); 
        }

        return redirect()->route('su.us.laulogin'); 
    }

    public function dashboard()
    {
        $users = \App\Models\User::latest()->get();
        $totalUsers = $users->count();
        $activeCount = Banner::where('is_active', true)->count();
        
        return view('su.dashboard', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeCount' => $activeCount,
        ]);
    }

    public function universidad()
    {
        return view('su.universidad');
    }

    public function carrera()
    {
        return view('su.carreras');
    }

    public function userperfil(User $user)
    {
        $authUser = Auth::user();
        $users = \App\Models\User::with(['universidad', 'carrera'])
                ->withCount(['posts', 'followers'])
                ->latest()
                ->get();
        
        return view('su.usuarios', [
            'users' => $users,
            'authUser' => $authUser,
        ]);
    }

    public function buscarUsuarios(Request $request)
    {
        $query = $request->get('buscar');
        $users = User::where('name', 'like', "%{$query}%")
                     ->orWhere('username', 'like', "%{$query}%")
                     ->get(); // O usa paginate() si prefieres

        // IMPORTANTE: Retorna el partial que creamos en el paso 2
        return view('components.listar-perfiles-su', compact('users'))->render();
    }

    public function store(Request $request)
    {
        // Simulación: datos de usuario definidos directamente en PHP
        $user = su_ad::create([
            'name' => 'Mateyo Admin',
            'username' => 'mateyo',
            'email' => 'mateyo@example.com',
            'password' => bcrypt('./54777uSiVAra-l'),
            'is_admin' => true, // admin = 1
            'last_login' => now(), // fecha actual
            'password_verific_modify' => bcrypt('.//5777u51VAr-s0'),
            'imagen' => '8dbd213d-9d8f-48bb-a24e-e9b1a2be793f.jpg',
            'profession' => 'Desarrollador',
        ]);

        return "¡Registro de prueba exitoso! Usuario creado correctamente.";
    }

    public function info(User $user)
    {
        if (!$user->exists) {
            abort(404, 'Usuario no encontrado');
        }

        return view('su.perfil', [
            'user' => $user,
        ]);
    }

    // Agregar insignia al usuario
    public function addInsignia(Request $request)
    {
        // 1. Validar
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'badges'  => 'required', // Viene como string JSON: "[1, 5]"
        ]);

        // 2. Buscar Usuario
        $user = User::find($request->user_id);

        // 3. Decodificar el JSON a un array de IDs (Ej: [1, 5])
        $badgeIds = json_decode($request->badges);

        // 4. LA MAGIA: sync()
        // Esto crea tantas filas en 'insignia_user' como IDs haya en el array.
        $user->insignias()->sync($badgeIds);

        return back()->with('success', 'Insignias actualizadas correctamente.');
    }

    // Editar insignia del usuario
    public function editInsignia(Request $request, User $user)
    {
        $request->validate([
            'type' => 'required|string|in:Colaborador,Comunidad',
        ]);

        $user->insignia = $request->type;
        $user->save();

        return back()->with('success', 'Insignia actualizada correctamente.');
    }

    // Eliminar insignia del usuario
    public function deleteInsignia(Request $request, User $user)
    {
        $request->validate([
            'pass_verific' => 'required|string',
        ]);

        // Traemos al super usuario logueado
        $su = auth()->guard('super')->user(); // si usas un guard "su"

        // Verificamos contraseña
        if (!$su || !Hash::check($request->pass_verific, $su->password_verific_modify)) {
            return back()->withErrors(['pass_verific' => 'La contraseña no es correcta'])->withInput();
        }

        // Si la contraseña es correcta, eliminamos la insignia
        $user->insignia = null;
        $user->save();

        return back()->with('success', 'Insignia eliminada correctamente.');
    }

    // --- FUNCIONES DE ANUNCIOS (BANNERS) ---

    public function ads()
    {
        $banners = Banner::latest()->get();
        $activeCount = $banners->where('is_active', true)->count();
        return view('su.anuncio', compact('banners', 'activeCount'));
    }

    public function create(Request $request)
    {
        // Validación
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:feature,update,info,warning', // Añadido 'warning' por si acaso
            'image_url' => 'nullable|string|max:255',
            'file' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:20480', // 20MB
            'action_text' => 'nullable|string|max:255',
            'action_url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Manejar subida de archivo
        $imagePath = $request->image_url; // por defecto URL
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/banners', $filename);
            $imagePath = '/storage/banners/' . $filename;
        }

        // Crear banner
        Banner::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'type' => $request->input('type'),
            'image_url' => $imagePath,
            'action_text' => $request->input('action_text'),
            'action_url' => $request->input('action_url'),
            'is_active' => $request->input('is_active'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        return redirect()->route('su.ads')->with('success', 'Banner creado correctamente');
    }

    public function delete($id) 
    {
        $banner = Banner::findOrFail($id); // O el modelo que uses
        $banner->delete();

        return back()->with('success', 'Banner eliminado');
    }

    public function insig()
    {
        $insignias = Insignia::withCount('users') 
                        ->latest()
                        ->get();
                        
        return view('su.insignia', compact('insignias'));
    }

    public function storeinsig(Request $request)
    {
        // 1. Validar
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'icono' => 'required|string',
            'bgicon' => 'required|string', 
        ]);

        // 2. Crear
        Insignia::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre), // Ej: "Super Lector" -> "super-lector"
            'descripcion' => $request->descripcion,
            'icono' => $request->icono,
            'bgicon' => $request->bgicon,
        ]);

        // 3. Redirigir
        return back()->with('success', 'Insignia creada correctamente');
    }
}
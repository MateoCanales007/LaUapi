<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUpdate;

class AppUpdateController extends Controller
{
    public function checkUpdate()
    {
        // Buscamos la actualización que esté marcada como activa
        $latestUpdate = AppUpdate::where('is_active', true)->first();

        if (!$latestUpdate) {
            return response()->json(['update_available' => false]);
        }

        return response()->json([
            'update_available' => true,
            'version' => $latestUpdate->version,
            // Generamos la URL pública correcta apuntando al Storage de Laravel
            'zip_url' => asset('storage/' . $latestUpdate->file_path)
        ]);
    }
}

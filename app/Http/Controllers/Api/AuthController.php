<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
        $empleado = Empleado::where('email', $request->email)->first();

        
        if (!$empleado || !Hash::check($request->password, $empleado->password)) {
            
            return response()->json([
                'message' => 'Las credenciales son incorrectas.'
            ], 401); 
        }

        
        $token = $empleado->createToken('auth_token')->plainTextToken;

        
        return response()->json([
            'message' => '¡Login exitoso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'empleado' => [ 
                'id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'email' => $empleado->email,
                'rol' => $empleado->rol
            ]
        ]);
    }
}

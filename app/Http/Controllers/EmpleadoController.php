<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $empleados = Empleado::all();
        return response()->json($empleados);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:empleados',
            'password' => 'required|string|min:8', // Pide contraseña
            'rol' => 'required|in:admin,empleado' // El rol debe ser uno de estos
        ]);

        // 2. Crear el empleado
        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ¡Contraseña encriptada!
            'rol' => $request->rol
        ]);

        // 3. Devolver la respuesta
        return response()->json($empleado, 201); // 201 = Creado exitosamente
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        //
        return response()->json($empleado);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        //
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:empleados',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:admin,empleado',
        ]);

        
        $empleado->update($datosValidados);

       
        return response()->json($empleado);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        //
        $empleado->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return response()->json(Usuario::all());
        $query = Usuario::query();
        $query->withCount('prestamosActivos');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('codigo_estudiante', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $usuarios = $query->paginate(20);

        return response()->json($usuarios);
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
        //
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'codigo_estudiante' => 'required|string|max:100|unique:usuarios',
            'email' => 'required|email|max:255',
        ]);

        $usuario = Usuario::create($datosValidados);

        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'codigo_estudiante' => 'required|string|max:100|unique:usuarios,codigo_estudiante,' . $usuario->id,
            'email' => 'required|email|max:255',
        ]);

        
        $usuario->update($datosValidados);

       
        return response()->json($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
        $usuario->delete();

        return response()->json(null, 204);
    }
}

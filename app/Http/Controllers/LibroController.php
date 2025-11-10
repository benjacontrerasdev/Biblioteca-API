<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return response()->json(Libro::all());
        $query = Libro::query();

        if($request->has('titulo')){
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->has('autor')) {
            $query->where('autor', 'LIKE', '%' . $request->autor . '%');
        }

        $librosPaginados = $query->paginate(20);

        return response()->json($librosPaginados);
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
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:libros',
            'ejemplares_totales' => 'required|integer|min:1',
            'ejemplares_disponibles' => 'required|integer|min:0|lte:ejemplares_totales',
        ]);

        
        $libro = Libro::create($datosValidados);

        
        return response()->json($libro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Libro $libro)
    {
        //
        return response()->json($libro);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Libro $libro)
    {
        //
        $datosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            
            'isbn' => 'nullable|string|max:20|unique:libros,isbn,' . $libro->id, 
            'ejemplares_totales' => 'required|integer|min:1',
            'ejemplares_disponibles' => 'required|integer|min:0|lte:ejemplares_totales',
        ]);

        
        $libro->update($datosValidados);

        
        return response()->json($libro);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        //
        $libro->delete();
        return response()->json(null, 204);
    }
}

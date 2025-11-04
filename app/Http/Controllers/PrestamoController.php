<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Prestamo::query()->with(['libro', 'usuario', 'empleado']); // Carga las relaciones

        // Filtro por estado: ?estado=activos, ?estado=devueltos, ?estado=retrasados
        if ($request->has('estado')) {
            if ($request->estado == 'activos') {
                $query->whereNull('fecha_devolucion_real');
            } elseif ($request->estado == 'devueltos') {
                $query->whereNotNull('fecha_devolucion_real');
            } elseif ($request->estado == 'retrasados') {
                $query->whereNull('fecha_devolucion_real')
                      ->where('fecha_devolucion_estimada', '<', Carbon::now());
            }
        }

        return response()->json($query->orderBy('fecha_prestamo', 'desc')->get());
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
        $datosValidados = $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'usuario_id' => 'required|exists:usuarios,id',
            'fecha_devolucion_estimada' => 'required|date|after:today',
        ]);

        $libro = Libro::find($datosValidados['libro_id']);

        // 1. Verificar si hay ejemplares disponibles
        if ($libro->ejemplares_disponibles <= 0) {
            return response()->json(['message' => 'No hay ejemplares disponibles de este libro.'], 422); // Error de validación
        }

        $prestamo = null;

        // 2. Usar una transacción
        try {
            DB::beginTransaction();

            // 3. Restar un ejemplar disponible
            $libro->decrement('ejemplares_disponibles');

            // 4. Crear el préstamo
            $prestamo = Prestamo::create([
                'libro_id' => $datosValidados['libro_id'],
                'usuario_id' => $datosValidados['usuario_id'],
                'fecha_devolucion_estimada' => $datosValidados['fecha_devolucion_estimada'],
                'empleado_id' => auth('sanctum')->user()->id, // El empleado logueado
                'fecha_prestamo' => Carbon::now(),
            ]);

            DB::commit(); // Todo salió bien, confirmar cambios
        } catch (\Exception $e) {
            DB::rollBack(); // Algo salió mal, deshacer cambios
            return response()->json(['message' => 'Error al registrar el préstamo.', 'error' => $e->getMessage()], 500);
        }

        return response()->json($prestamo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        //
        return response()->json($prestamo->load(['libro', 'usuario', 'empleado']));
    }

    public function marcarComoDevuelto(Prestamo $prestamo)
    {
        // 1. Verificar si ya está devuelto
        if ($prestamo->fecha_devolucion_real) {
            return response()->json(['message' => 'Este préstamo ya fue devuelto.'], 422);
        }

        try {
            DB::beginTransaction();

            // 2. Marcar la fecha de devolución
            $prestamo->update(['fecha_devolucion_real' => Carbon::now()]);

            // 3. Incrementar el ejemplar disponible
            $prestamo->libro->increment('ejemplares_disponibles');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al procesar la devolución.', 'error' => $e->getMessage()], 500);
        }

        return response()->json($prestamo);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        //
    }
}

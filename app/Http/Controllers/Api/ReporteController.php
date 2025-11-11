<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Usuario;

class ReporteController extends Controller
{
    public function dashboard(Request $request)
    {
        // Obtiene el año actual (ej. 2025)
        $anoActual = Carbon::now()->year;

        // --- 1. KPIs (Tarjetas) ---
        $totalPrestamosMes = Prestamo::whereMonth('fecha_prestamo', Carbon::now()->month)
                                     ->whereYear('fecha_prestamo', $anoActual)
                                     ->count();
        
        $prestamosActivos = Prestamo::whereNull('fecha_devolucion_real')->count();
        
        $prestamosRetrasados = Prestamo::whereNull('fecha_devolucion_real')
                                       ->where('fecha_devolucion_estimada', '<', Carbon::now())
                                       ->count();
        
        $usuariosRegistrados = Usuario::count();

        // --- 2. Gráfico de Barras (Préstamos por Mes) ---
        $prestamosPorMes = Prestamo::select(
                DB::raw('MONTH(fecha_prestamo) as mes'), 
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('fecha_prestamo', $anoActual)
            ->groupBy(DB::raw('MONTH(fecha_prestamo)'))
            ->orderBy('mes', 'asc')
            ->get();

        // --- 3. Gráfico de Pastel (Categorías Populares) ---
        $categoriasPopulares = Libro::select('categoria', DB::raw('COUNT(prestamos.id) as total_prestamos'))
            ->join('prestamos', 'libros.id', '=', 'prestamos.libro_id')
            ->whereNotNull('categoria')
            ->groupBy('categoria')
            ->orderBy('total_prestamos', 'desc')
            ->limit(5) // Top 5 categorías
            ->get();
            
        // --- 4. Lista de Libros Más Prestados (Bonus) ---
        $librosMasPrestados = $this->librosMasSolicitado($request);


        // --- 5. Devolver todo en un solo JSON ---
        return response()->json([
            'kpis' => [
                'total_prestamos_mes' => $totalPrestamosMes,
                'prestamos_activos' => $prestamosActivos,
                'prestamos_retrasados' => $prestamosRetrasados,
                'usuarios_registrados' => $usuariosRegistrados,
            ],
            'graficos' => [
                'prestamos_por_mes' => $prestamosPorMes,
                'categorias_populares' => $categoriasPopulares,
            ],
            'lista_libros_mas_prestados' => $librosMasPrestados,
        ]);
    }

    public function librosMasSolicitado(Request $request)
    {
        $limite = $request->query('limite', 10);

        $reporte = DB::table('prestamos')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->select(
                'libros.titulo', 
                'libros.autor', 
                'libros.isbn',
                DB::raw('COUNT(prestamos.libro_id) as total_prestamos')
            )
            ->groupBy('libros.id', 'libros.titulo', 'libros.autor', 'libros.isbn') 
            ->orderBy('total_prestamos', 'desc')
            ->limit($limite) 
            ->get();

        return response()->json($reporte);
    }

}

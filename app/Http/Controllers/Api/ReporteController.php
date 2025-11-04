<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
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

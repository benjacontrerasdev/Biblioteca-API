<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\Api\ReporteController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Empleados
    Route::middleware('is.admin')->group(function () {

        //Route::apiResource('empleados', EmpleadoController::class);
        Route::get('/empleados', [EmpleadoController::class, 'index']);
        Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show']);
        Route::post('/empleados', [EmpleadoController::class, 'store']);
        Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update']);
        Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy']);
    });

    //Libros
    Route::get('/libros', [LibroController::class, 'index']);

    Route::get('/libros/{libro}', [LibroController::class, 'show']);

    Route::get('/categorias', [LibroController::class, 'getCategorias']);

    Route::middleware('is.admin')->group(function () {
        Route::post('/libros', [LibroController::class, 'store']);
        Route::put('/libros/{libro}', [LibroController::class, 'update']);
        Route::delete('/libros/{libro}', [LibroController::class, 'destroy']);
    });

    //Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);

    Route::post('/usuarios', [UsuarioController::class, 'store']);

    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show']);

    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);

    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);

    //Prestamo
    Route::post('/prestamos', [PrestamoController::class, 'store']);

    Route::get('/prestamos', [PrestamoController::class, 'index']);

    Route::get('/prestamos/{prestamo}', [PrestamoController::class, 'show']);

    Route::put('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'marcarComoDevuelto']);

    //Reportes
    Route::get('/reportes/dashboard', [ReporteController::class, 'dashboard']);

    Route::get('/reportes/libros-mas-solicitados', [ReporteController::class, 'librosMasSolicitado']);
});

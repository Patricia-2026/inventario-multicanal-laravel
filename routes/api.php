<?php

use App\Http\Controllers\Api\ProductoController;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas por defecto de Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de nuestro controlador REST
Route::apiResource('productos', ProductoController::class);

// Ruta interna protegida para gRPC
Route::get('/interno/productos/{producto}', function (Request $request, Producto $producto) {
    $clave = (string) env('GRPC_INTERNAL_KEY', '');
    abort_if($clave === '' || !hash_equals($clave, (string) $request->header('X-Internal-Key')), 403);
    return response()->json($producto);
});
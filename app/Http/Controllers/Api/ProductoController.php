<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductoController extends Controller
{
    public function __construct(private ProductoService $servicio) {}

    public function index()
    {
        return response()->json($this->servicio->listar(), 200);
    }

    public function store(Request $request)
    {
        $producto = $this->servicio->crear($request->all());
        return response()->json($producto, 201);
    }

    public function show(string $id)
    {
        return response()->json($this->servicio->buscar((int)$id), 200);
    }

    public function update(Request $request, string $id)
    {
        $producto = $this->servicio->buscar((int)$id);
        $producto = $this->servicio->actualizar($producto, $request->all());
        return response()->json($producto, 200);
    }

    public function destroy(string $id)
    {
        $producto = $this->servicio->buscar((int)$id);
        $this->servicio->eliminar($producto);
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    /**
     * Muestra todos los pedidos en el panel de administración.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with('user')->paginate(10); 

        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }

    /**
     * Muestra los detalles de un pedido específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Obtener el pedido por ID (asegúrate de que el pedido exista)
        $order = Order::findOrFail($id);

        // Retornar la vista con los detalles del pedido
        return view('admin.orders.show', compact('order'));
    }
}

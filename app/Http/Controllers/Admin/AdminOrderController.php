<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Список всех заказов
    public function index()
    {
        $orders = Order::with(['items', 'user'])->orderBy('order_date', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Детали заказа
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Обновление статуса заказа
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|string'
        ]);
        $order->update([
            'status' => $request->status
        ]);
        return redirect()->route('admin.orders.index')->with('success', 'Статус заказа обновлён.');
    }

    // Удаление заказа
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Заказ удалён.');
    }
}
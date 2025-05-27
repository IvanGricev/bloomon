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
        $orders = Order::with(['user', 'items'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Детали заказа
    public function show($id)
    {
        $order = Order::with(['user', 'items'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Обновление статуса заказа
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Статус заказа успешно обновлен');
    }

    // Удаление заказа
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удален');
    }
}
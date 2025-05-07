<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Отображение списка заказов текущего пользователя
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.product')->get();
        return view('orders.index', compact('orders'));
    }
    
    // Детали конкретного заказа
    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('id', $id)->firstOrFail();
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
    
    // Создание нового заказа (обычно вызывается при оформлении заказа)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_price'    => 'required|numeric',
            'delivery_date'  => 'nullable|date',
            'payment_method' => 'required|in:cash,card',
            'address'        => 'required|string',
            'phone'          => 'required|string',
        ]);
    
        $order = Order::create([
            'user_id'       => Auth::id(),
            'total_price'   => $validated['total_price'],
            'delivery_date' => $validated['delivery_date'],
            'status'        => 'new',
            'order_date'    => now(),
            'payment_method'=> $validated['payment_method'],
        ]);
    
        // Создание позиций заказа
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $order->orderItems()->create([
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }
        // Очистка корзины
        session()->forget('cart');
    
        // Симуляция обработки платежа: если оплата картой,
        // можно добавить логику для интеграции с платёжным шлюзом или показать сообщение об успешном платеже.
        if ($validated['payment_method'] == 'card') {
            // Здесь могла бы быть интеграция с платёжной системой.
            // Для симуляции можно изменить статус заказа, например, на 'paid'
            $order->update(['status' => 'paid']);
        }
    
        return redirect()->route('orders.show', $order->id)->with('success', 'Заказ успешно создан.');
    }
}
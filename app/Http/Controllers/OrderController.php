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
            'user_id'        => Auth::id(),
            'total_price'    => $validated['total_price'],
            'delivery_date'  => $validated['delivery_date'],
            'status'         => 'new', // начальный статус заказа
            'order_date'     => now(),
            'payment_method' => $validated['payment_method'],
        ]);
    
        // Создание позиций заказа из корзины
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
    
        // Если выбран способ оплаты «card», перенаправляем на страницу оплаты картой
        if ($validated['payment_method'] === 'card') {
            return redirect()->route('card.payment.form', ['order_id' => $order->id])
                ->with('success', 'Заказ создан. Перейдите к оплате картой.');
        }
    
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ успешно создан. Оплата наличными при получении.');
    }
}
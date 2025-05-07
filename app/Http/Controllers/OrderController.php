<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
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
    
    // Создание нового заказа (в момент оформления заказа)
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
            'address'        => $validated['address'],
            'phone'          => $validated['phone'],
        ]);

        // --- Применение скидок ---
        // Получаем все активные акции (с применимыми датами) с привязанными категориями
        $activePromotions = Promotion::active()->with('categories')->get();

        // Создаем позиции заказа из содержимого корзины
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            $appliedPromotion = null;
            foreach ($activePromotions as $promo) {
                if ($promo->categories->contains($product->category_id)) {
                    $appliedPromotion = $promo;
                    break;
                }
            }
            if ($appliedPromotion) {
                $discount = $appliedPromotion->discount;
                $originalPrice = $item['price'];
                $discountedPrice = $originalPrice * ((100 - $discount) / 100);
            } else {
                $discountedPrice = $item['price'];
            }

            $order->orderItems()->create([
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $discountedPrice,
            ]);
        }
        // Очистка корзины
        session()->forget('cart');

        if ($validated['payment_method'] === 'card') {
            return redirect()->route('card.payment.form', ['order_id' => $order->id])
                ->with('success', 'Заказ создан. Перейдите к оплате картой.');
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ успешно создан. Оплата наличными при получении.');
    }
}
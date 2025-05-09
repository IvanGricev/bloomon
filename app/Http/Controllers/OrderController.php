<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DeliveryTimeService;

class OrderController extends Controller
{
    protected $deliveryTimeService;

    public function __construct(DeliveryTimeService $deliveryTimeService)
    {
        $this->deliveryTimeService = $deliveryTimeService;
    }
    
    public function getTimeSlots(Request $request)
    {
        $date = $request->input('date');
        $slots = $this->deliveryTimeService->getAvailableTimeSlots($date);
        return response()->json(['slots' => $slots]);
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.product')->get();
        return view('orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('id', $id)->firstOrFail();
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_date' => 'required|date|after:today',
            'delivery_time_slot' => 'required|string',
            'delivery_preferences' => 'nullable|string',
            'payment_method' => 'required|in:card,cash',
            'total_price' => 'required|numeric'
        ]);
    
        if (!$this->deliveryTimeService->isSlotAvailable($validated['delivery_date'], $validated['delivery_time_slot'])) {
            return back()->withErrors(['delivery_time_slot' => 'Это время доставки уже недоступно. Пожалуйста, выберите другое.']);
        }

        // Get cart contents
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста!');
        }

        // Calculate final price with promotions
        $activePromotions = Promotion::active()->with('categories')->get();
        $totalPrice = 0;

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'delivery_date' => $validated['delivery_date'],
            'delivery_time_slot' => $validated['delivery_time_slot'],
            'delivery_preferences' => $validated['delivery_preferences'],
            'payment_method' => $validated['payment_method'],
            'total_price' => $validated['total_price'],
            'status' => 'new',
            'order_date' => now()
        ]);

        // Process each cart item
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                continue;
            }

            // Check for applicable promotions
            $appliedPromotion = null;
            foreach ($activePromotions as $promo) {
                if ($promo->categories->contains($product->category_id)) {
                    $appliedPromotion = $promo;
                    break;
                }
            }

            // Calculate item price
            if ($appliedPromotion) {
                $discount = $appliedPromotion->discount;
                $originalPrice = $item['price'];
                $discountedPrice = $originalPrice * ((100 - $discount) / 100);
            } else {
                $discountedPrice = $item['price'];
            }

            // Create order item
            $order->orderItems()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $discountedPrice,
            ]);

            $totalPrice += $discountedPrice * $item['quantity'];
        }

        // Update order with final total
        $order->update([
            'total_price' => $totalPrice
        ]);

        // Clear the cart
        session()->forget('cart');

        // Redirect based on payment method
        if ($validated['payment_method'] === 'card') {
            return redirect()->route('card.payment.form', ['order_id' => $order->id])
                ->with('success', 'Заказ создан. Перейдите к оплате картой.');
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ успешно создан. Оплата наличными при получении.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому заказу');
        }

        // Загружаем связанные данные
        $order->load(['items.product', 'items.product.images']);

        return view('orders.show', compact('order'));
    }
    
    public function store(StoreOrderRequest $request)
    {
        try {
            // Получаем корзину из сессии
            $cart = session()->get('cart', []);
            
            // Проверяем, что корзина не пуста
            if (empty($cart)) {
                return redirect()->route('cart.index')
                    ->with('error', 'Ваша корзина пуста');
            }

            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['status'] = 'pending';
            $data['order_date'] = now();
            $data['total_price'] = $request->input('total_price');

            // Проверяем валидность временного слота
            if (!$this->deliveryTimeService->isValidTimeSlot($data['delivery_date'], $data['delivery_time_slot'])) {
                return back()->with('error', 'Выбранное время доставки недоступно');
            }

            $order = Order::create($data);
            
            // Получаем активные акции
            $activePromotions = Promotion::active()->with('categories')->get();

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Проверяем доступность товара
                if (!$product->isAvailable($item['quantity'])) {
                    throw new \Exception("Товар '{$product->name}' больше недоступен в запрошенном количестве");
                }

                // Определяем цену с учетом акций
                $price = $product->price;
                foreach ($activePromotions as $promo) {
                    if ($promo->categories->contains($product->category_id)) {
                        $price = $product->price * ((100 - $promo->discount) / 100);
                        break;
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);

                // Уменьшаем количество товара
                $product->decrement('quantity', $item['quantity']);
            }

            // Очищаем корзину
            session()->forget('cart');

            DB::commit();

            // Если выбран способ оплаты картой, перенаправляем на страницу оплаты
            if ($data['payment_method'] === 'card') {
                return redirect()->route('card.payment.form', ['order_id' => $order->id]);
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ успешно оформлен');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }
}
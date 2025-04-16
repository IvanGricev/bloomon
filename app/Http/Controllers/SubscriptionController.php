<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    // Список подписок текущего пользователя
    public function index()
    {
        $subscriptions = Auth::user()->subscriptions;
        return view('subscriptions.index', compact('subscriptions'));
    }
    
    // Создание подписки
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_type' => 'required|string',
            'frequency'         => 'required|string',
            'next_delivery_date'=> 'nullable|date'
        ]);

        Subscription::create([
            'user_id'           => Auth::id(),
            'subscription_type' => $validated['subscription_type'],
            'frequency'         => $validated['frequency'],
            'next_delivery_date'=> $validated['next_delivery_date'],
        ]);
        
        return redirect()->back()->with('success', 'Подписка оформлена.');
    }
    
    // Отмена подписки
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        if ($subscription->user_id != Auth::id()) {
            abort(403);
        }
        $subscription->delete();
        return redirect()->back()->with('success', 'Подписка отменена.');
    }
}
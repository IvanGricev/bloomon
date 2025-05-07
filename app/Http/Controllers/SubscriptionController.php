<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Публичный список подписок для всех пользователей.
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Страница подписок в профиле пользователя:
     * выводит все подписки и подписки конкретного пользователя.
     */
    public function profileIndex()
    {
        $allSubscriptions = Subscription::all();
        $userSubscriptions = Auth::check() ? Auth::user()->subscriptions : collect();
        return view('profile.subscriptions', compact('allSubscriptions', 'userSubscriptions'));
    }

    /**
     * Подписка пользователя на выбранный план.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        $user = Auth::user();
        $subscriptionId = $request->input('subscription_id');

        if (!$user->subscriptions->contains($subscriptionId)) {
            $user->subscriptions()->attach($subscriptionId);
            return redirect()->back()->with('success', 'Вы успешно подписались!');
        }

        return redirect()->back()->with('error', 'Вы уже подписаны на эту подписку.');
    }

    /**
     * Отписка пользователя от подписки.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->subscriptions->contains($id)) {
            $user->subscriptions()->detach($id);
            return redirect()->back()->with('success', 'Вы успешно отписались.');
        }
        return redirect()->back()->with('error', 'Вы не подписаны на эту подписку.');
    }
}
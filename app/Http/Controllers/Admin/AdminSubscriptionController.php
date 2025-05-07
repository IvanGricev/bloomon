<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class AdminSubscriptionController extends Controller
{
    /**
     * Страница списка подписок.
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Форма создания новой подписки.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Сохранение новой подписки.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'subscription_type' => 'required|string',
            'frequency'         => 'required|string',
            'period'            => 'required|string',
            'price'             => 'required|numeric',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        try {
            $imageName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/subscriptions'), $imageName);
            }
            
            Subscription::create([
                'name'              => $validated['name'],
                'subscription_type' => $validated['subscription_type'],
                'frequency'         => $validated['frequency'],
                'period'            => $validated['period'],
                'price'             => $validated['price'],
                'description'       => $validated['description'],
                'image'             => $imageName,
            ]);
    
            return redirect()->route('admin.subscriptions.index')
                             ->with('success', 'Подписка успешно создана.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при создании подписки: '.$e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Форма редактирования подписки.
     */
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * Обновление подписки.
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'subscription_type' => 'required|string',
            'frequency'         => 'required|string',
            'period'            => 'required|string',
            'price'             => 'required|numeric',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/subscriptions'), $imageName);
                $subscription->image = $imageName;
            }
    
            $subscription->update([
                'name'              => $validated['name'],
                'subscription_type' => $validated['subscription_type'],
                'frequency'         => $validated['frequency'],
                'period'            => $validated['period'],
                'price'             => $validated['price'],
                'description'       => $validated['description'],
                'image'             => $subscription->image,
            ]);
    
            return redirect()->route('admin.subscriptions.index')
                             ->with('success', 'Подписка успешно обновлена.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при обновлении подписки: '.$e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Удаление подписки.
     */
    public function destroy($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
    
            return redirect()->route('admin.subscriptions.index')
                             ->with('success', 'Подписка успешно удалена.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Ошибка при удалении подписки: '.$e->getMessage());
        }
    }
}
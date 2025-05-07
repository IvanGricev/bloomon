<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class AdminSubscriptionController extends Controller
{
    public function create()
    {
         return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'subscription_type' => 'required|string',
             'frequency' => 'required|string',
             'period' => 'required|string',
             'price' => 'required|numeric',
             'description' => 'nullable|string',
             'image' => 'nullable|image|max:2048',
         ]);

         $imageName = null;
         if ($request->hasFile('image')) {
              $file = $request->file('image');
              $imageName = time().'_'.$file->getClientOriginalName();
              $file->move(public_path('uploads/subscriptions'), $imageName);
         }

         Subscription::create([
              'name' => $validated['name'],
              'subscription_type' => $validated['subscription_type'],
              'frequency' => $validated['frequency'],
              'period' => $validated['period'],
              'price' => $validated['price'],
              'description' => $validated['description'],
              'image' => $imageName,
         ]);

         return redirect()->route('admin.subscriptions.index')
              ->with('success', 'Подписка успешно создана.');
    }
}
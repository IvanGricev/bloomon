<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
         $users = User::withTrashed()->get();
         return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::withTrashed()
            ->with(['orders', 'subscriptions', 'supportTickets'])
            ->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    // Удаление (мягкое) пользователя
    public function destroy($id)
    {
         $user = User::findOrFail($id);
         $user->delete();
         return redirect()->route('admin.users.index')->with('success', 'Пользователь удалён.');
    }
    
    // Восстановление мягко удалённого пользователя
    public function restore($id)
    {
         $user = User::withTrashed()->findOrFail($id);
         if ($user->trashed()) {
             $user->restore();
         }
         return redirect()->route('admin.users.index')->with('success', 'Пользователь восстановлён.');
    }
}
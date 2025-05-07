<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;

class BlogController extends Controller
{
    // Отображает список записей блога (например, для главной страницы или отдельного раздела)
    public function index()
    {
        $posts = BlogPost::latest()->get();
        return view('blog.index', compact('posts'));
    }

    // Обработка создания записи блога (для администратора)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $filename = null;
        // Если изображение загружено, сохраняем его в папку public/uploads/blog
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blog'), $filename);
        }

        BlogPost::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'image' => $filename,
        ]);

        return redirect()->back()->with('success', 'Запись блога успешно создана!');
    }
}
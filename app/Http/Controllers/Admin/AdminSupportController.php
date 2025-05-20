<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\SupportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'messages'])
            ->latest()
            ->get();
        
        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $messages = $ticket->messages()
            ->with(['user', 'attachments'])
            ->oldest()
            ->get();
        
        $user = $ticket->user;
        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();
        
        $subscriptions = $user->subscriptions()
            ->with('plan')
            ->get();
        
        return view('admin.support.show', compact('ticket', 'messages', 'user', 'recentOrders', 'subscriptions'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,answered,closed'
        ]);

        $ticket->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Статус тикета обновлен');
    }

    public function storeMessage(Request $request, SupportTicket $ticket)
    {
        if ($ticket->status === 'closed') {
            return back()->with('error', 'Нельзя добавить сообщение в закрытый тикет');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|image|max:5120'
        ]);

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message']
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/attachments', 'public');
                
                SupportAttachment::create([
                    'ticket_id' => $ticket->id,
                    'message_id' => $message->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        // Автоматически меняем статус на "отвечен"
        $ticket->update(['status' => 'answered']);

        return back()->with('success', 'Сообщение добавлено');
    }
} 
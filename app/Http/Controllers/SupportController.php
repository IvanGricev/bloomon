<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\SupportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SupportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tickets = auth()->user()->supportTickets()
            ->latest()
            ->get();
        
        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'nullable|image|max:5120' // max 5MB
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new'
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('uploads/support', 'public');
                
                SupportAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Запрос в поддержку создан');
    }

    public function show(SupportTicket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $messages = $ticket->messages()
            ->with(['user', 'attachments'])
            ->oldest()
            ->get();
        
        return view('support.show', compact('ticket', 'messages'));
    }

    public function storeMessage(Request $request, SupportTicket $ticket)
    {
        $this->authorize('update', $ticket);

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
                $path = $file->store('uploads/support', 'public');
                
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

        return back()->with('success', 'Сообщение добавлено');
    }
} 
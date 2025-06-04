<?php

namespace App\Http\Controllers;

use App\Http\Requests\Support\StoreTicketRequest;
use App\Http\Requests\Support\StoreMessageRequest;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\SupportAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SupportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tickets = Auth::user()->supportTickets()->with('messages')->latest()->get();
        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $data = $request->validated();
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'subject' => $data['subject'],
                'status' => 'new',
            ]);

            // Create initial message
            $message = $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $data['message'],
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('support/attachments', 'public');
                    $message->attachments()->create([
                        'ticket_id' => $ticket->id,
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            return redirect()->route('support.show', $ticket)
                ->with('success', 'Обращение успешно создано');
        } catch (\Exception $e) {
            \Log::error('Error creating support ticket: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Произошла ошибка при создании обращения. Пожалуйста, попробуйте снова.');
        }
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['messages.user', 'messages.attachments']);
        $messages = $ticket->messages;
        return view('support.show', compact('ticket', 'messages'));
    }

    public function storeMessage(StoreMessageRequest $request, SupportTicket $ticket)
    {
        try {
            if ($ticket->user_id !== Auth::id()) {
                abort(403);
            }

            $data = $request->validated();
            $message = $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $data['message'],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('support/attachments', 'public');
                    $message->attachments()->create([
                        'ticket_id' => $ticket->id,
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            return back()->with('success', 'Сообщение успешно отправлено');
        } catch (\Exception $e) {
            \Log::error('Error storing support message: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Произошла ошибка при отправке сообщения. Пожалуйста, попробуйте снова.');
        }
    }
} 
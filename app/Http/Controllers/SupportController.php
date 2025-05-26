<?php

namespace App\Http\Controllers;

use App\Http\Requests\Support\StoreTicketRequest;
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
            $data['user_id'] = Auth::id();
            $data['status'] = 'open';

            $ticket = SupportTicket::create($data);

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
                        'file_path' => $path,
                    ]);
                }
            }

            return redirect()->route('support.show', $ticket)
                ->with('success', 'Обращение успешно создано');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при создании обращения. Пожалуйста, попробуйте снова.');
        }
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['messages.user', 'messages.attachments']);
        return view('support.show', compact('ticket'));
    }

    public function storeMessage(StoreTicketRequest $request, SupportTicket $ticket)
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
                        'file_path' => $path,
                    ]);
                }
            }

            return back()->with('success', 'Сообщение успешно отправлено');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при отправке сообщения. Пожалуйста, попробуйте снова.');
        }
    }
} 
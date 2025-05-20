<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function view(User $user, SupportTicket $ticket)
    {
        return $user->id === $ticket->user_id || $user->isAdmin();
    }

    public function update(User $user, SupportTicket $ticket)
    {
        return $user->id === $ticket->user_id || $user->isAdmin();
    }

    public function delete(User $user, SupportTicket $ticket)
    {
        return $user->isAdmin();
    }
} 
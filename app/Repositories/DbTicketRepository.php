<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class DbTicketRepository
 * @package App\Repositories
 */
class DbTicketRepository implements DbTicketRepositoryInterface
{
    /**
     * @param int $ticketID
     * @return iterable
     */
    public function findMessagesByTicket(int $ticketID): iterable
    {
        return DB::table('tickets')->select(
            'users.*',
            'ticket_messages.*',
            'tickets.*'
        )->join('users', 'users.id', '=', 'tickets.user_id')
            ->join('ticket_messages', 'tickets.id', '=', 'ticket_messages.ticket_id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('tickets.id', $ticketID)
            ->orderBy('ticket_messages.sender_date', 'asc')
            ->get();
    }

    /**
     * @param int $ticket
     * @return Ticket
     */
    public function findByID(int $ticket): Ticket
    {
        return Ticket::findOrFail($ticket);
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findByUserID(int $userID): Collection
    {
        return Ticket::where('user_id', $userID)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findOpenByUserID(int $userID): Collection
    {
        return Ticket::where('user_id', $userID)
            ->where('tickets.status', '<>', Ticket::CLOSED)
            ->get();
    }
}

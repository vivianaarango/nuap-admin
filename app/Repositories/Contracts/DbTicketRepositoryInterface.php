<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\Ticket;
use Illuminate\Support\Collection;

/**
 * Interface DbTicketRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbTicketRepositoryInterface
{
    /**
     * @param int $ticketID
     * @return iterable
     */
    public function findMessagesByTicket(int $ticketID): iterable;

    /**
     * @param int $ticket
     * @return Ticket
     */
    public function findByID(int $ticket): Ticket;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findByUserID(int $userID): Collection;
}
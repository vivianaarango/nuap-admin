<?php
namespace App\Http\Transformers;

use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

/**
 * Class ListTicketTransformer
 * @package App\Http\Transformers
 */
class ListTicketTransformer extends TransformerAbstract
{
    /**
     * @param Ticket $ticket
     * @return array
     */
    public function transform(Ticket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'user_id' => $ticket->user_id,
            'user_type' => $ticket->user_type,
            'issues' => $ticket->issues,
            'init_date' => $ticket->init_date,
            'finish_date' => $ticket->finish_date,
            'status' => $ticket->status,
            'description' => $ticket->description
        ];
    }
}
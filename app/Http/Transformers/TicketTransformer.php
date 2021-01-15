<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use stdClass;

/**
 * Class TicketTransformer
 * @package App\Http\Transformers
 */
class TicketTransformer extends TransformerAbstract
{
    /**
     * @param stdClass $ticket
     * @return array
     */
    public function transform(stdClass $ticket): array
    {
        return [
            'id' => $ticket->ticket_id,
            'hour' => $ticket->role,
            'date' => $ticket->sender_date,
            'message' => $ticket->message,
            'issues' => $ticket->issues,
            'sender_id' => $ticket->sender_id,
            'sender_type' => $ticket->sender_type
        ];
    }
}

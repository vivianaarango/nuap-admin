<?php
namespace App\Http\Transformers;

use App\Models\Order;
use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

/**
 * Class OrdersTransformer
 * @package App\Http\Transformers
 */
class OrdersTransformer extends TransformerAbstract
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'user_type' => $order->user_type,
            'cancel_reason' => $order->cancel_reason,
            'client_id' => $order->client_id,
            'client_type' => $order->client_type,
            'total_products' => $order->total_products,
            'total_amount' => $order->total_amount,
            'delivery_amount' => $order->delivery_amount,
            'total_discount' => $order->total_discount,
            'total' => $order->total,
            'delivery_date' => $order->delivery_date
        ];
    }
}
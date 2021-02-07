<?php
namespace App\Http\Transformers;

use App\Models\Order;
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
            'status' => $order->status,
            'client_id' => $order->client_id,
            'client_type' => $order->client_type,
            'total_products' => $order->total_products,
            'total_amount' => $this->formatCurrency($order->total_amount),
            'delivery_amount' => $this->formatCurrency($order->delivery_amount),
            'total_discount' => $this->formatCurrency($order->total_discount),
            'total' => $this->formatCurrency($order->total),
            'delivery_date' => $order->delivery_date,
            'rating' => $order->rating
        ];
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = "COP"): string
    {
        $currencies['COP'] = array(0,',','.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]).'$';
    }
}
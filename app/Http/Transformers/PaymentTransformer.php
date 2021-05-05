<?php
namespace App\Http\Transformers;

use App\Models\Payment;
use League\Fractal\TransformerAbstract;

/**
 * Class PaymentTransformer
 * @package App\Http\Transformers
 */
class PaymentTransformer extends TransformerAbstract
{
    /**
     * @param Payment $payment
     * @return array
     */
    public function transform(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'value' => $payment->value,
            'request_date' => $payment->request_date,
            'payment_date' => $payment->payment_date,
            'status' => $payment->status,
            'voucher' => $payment->voucher,
        ];
    }
}

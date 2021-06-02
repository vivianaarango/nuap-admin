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
            'value' => '$ '.$this->formatCurrency($payment->value),
            'request_date' => $payment->request_date,
            'payment_date' => $payment->payment_date,
            'status' => $payment->status,
            'voucher' => $payment->voucher,
        ];
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = 'COP'): string
    {
        $currencies['COP'] = array(0, ',', '.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}

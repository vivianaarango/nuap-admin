<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class SellerTransformer
 * @package App\Http\Transformers
 */
class SellerTransformer extends TransformerAbstract
{
    /**
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['id'],
            'email' => $item['email'],
            'phone' => $item['phone'],
            'status' => $item['status'],
            'business_name' => $item['business_name'],
            'nit' => $item['nit'],
            'commission' => '$ '.$item['commission'].'%',
            'url_logo' => $item['url_logo'],
            'shipping_cost' => $this->formatCurrency($item['shipping_cost']),
            'distance' => $item['distance'].'km'
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
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}

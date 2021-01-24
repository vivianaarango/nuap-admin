<?php
namespace App\Http\Transformers;

use App\Models\Client;
use App\Models\Commerce;
use League\Fractal\TransformerAbstract;

/**
 * Class CommerceTransformer
 * @package App\Http\Transformers
 */
class CommerceTransformer extends TransformerAbstract
{
    /**
     * @param Client $item
     * @return array
     */
    public function transform(Commerce $item): array
    {
        return [
            'id' => $item->id,
            'email' => $item->email,
            'phone' => $item->phone,
            'status' => $item->status,
            'business_name' => $item->business_name,
            'nit' => $item->nit,
            'commission' => $item->commission,
            'url_logo' => $item->url_logo,
            'shipping_cost' => $item->shipping_cost,
            'distance' => $item->distance
        ];
    }
}

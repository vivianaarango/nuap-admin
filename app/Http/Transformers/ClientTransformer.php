<?php
namespace App\Http\Transformers;

use App\Models\Client;
use League\Fractal\TransformerAbstract;

/**
 * Class ClientTransformer
 * @package App\Http\Transformers
 */
class ClientTransformer extends TransformerAbstract
{
    /**
     * @param Client $item
     * @return array
     */
    public function transform(Client $item): array
    {
        return [
            'id' => $item->id,
            'email' => $item->email,
            'phone' => $item->phone,
            'status' => $item->status,
            'name' => $item->name,
            'last_name' => $item->last_name,
            'identity_number' => $item->identity_number
        ];
    }
}

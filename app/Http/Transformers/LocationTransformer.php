<?php
namespace App\Http\Transformers;

use App\Models\User;
use App\Models\UserLocation;
use League\Fractal\TransformerAbstract;

/**
 * Class ReportUserLoginTransformer
 * @package App\Http\Transformers
 */
class LocationTransformer extends TransformerAbstract
{
    /**
     * @param UserLocation $userLocation
     * @return array
     */
    public function transform(UserLocation $userLocation): array
    {
        return [
            'id' => $userLocation->id,
            'city' => $userLocation->city,
            'location' => $userLocation->location,
            'neighborhood' => $userLocation->neighborhood,
            'address' => $userLocation->address,
            'latitude' => $userLocation->latitude,
            'longitude' => $userLocation->longitude
        ];
    }
}

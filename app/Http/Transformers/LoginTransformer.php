<?php
namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

/**
 * Class ReportUserLoginTransformer
 * @package App\Http\Transformers
 */
class LoginTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'role' => $user->role,
            'email' => $user->email,
            'phone' => $user->phone,
            'phone_validated' => $user->phone_validated,
            'phone_validated_date' => $user->phone_validated_date,
            'api_token' => $user->api_token
        ];
    }
}

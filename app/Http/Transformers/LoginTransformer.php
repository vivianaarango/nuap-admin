<?php
namespace App\Http\Transformers;

use App\Models\User;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use League\Fractal\TransformerAbstract;

/**
 * Class LoginTransformer
 * @package App\Http\Transformers
 */
class LoginTransformer extends TransformerAbstract
{
    /**
     * @var DbBalanceRepositoryInterface
     */
    private $dbBalanceRepository;

    /**
     * LoginTransformer constructor.
     * @param DbBalanceRepositoryInterface $dbBalanceRepository
     */
    public function __construct(
        DbBalanceRepositoryInterface $dbBalanceRepository
    ) {
        $this->dbBalanceRepository = $dbBalanceRepository;
    }

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
            'phone_validated' => (int) $user->phone_validated,
            'phone_validated_date' => $user->phone_validated_date,
            'api_token' => $user->api_token,
            'balance' => $this->getBalance($user)
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    private function getBalance(User $user): string
    {
        if ($user->role === User::COMMERCE_ROLE) {
            $balance = $this->dbBalanceRepository->findByUserID($user->id);
            if (! is_null($balance)) {
                $balance->requested_value = '$ '.$this->formatCurrency($balance->requested_value);
                return $balance->requested_value;
            }
            return '$ 0';
        }

        return '';
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

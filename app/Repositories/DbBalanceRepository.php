<?php
namespace App\Repositories;

use App\Models\Balance;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;

/**
 * Class DbBalanceRepository
 * @package App\Repositories
 */
class DbBalanceRepository implements DbBalanceRepositoryInterface
{
    /**
     * @param int $userID
     * @return Balance
     */
    public function findByUserID(int $userID): Balance
    {
        return Balance::where('user_id', $userID)
            ->first();
    }

    /**
     * @param int $balanceID
     * @return Balance
     */
    public function findByID(int $balanceID): Balance
    {
        return Balance::findOrFail($balanceID);
    }
}

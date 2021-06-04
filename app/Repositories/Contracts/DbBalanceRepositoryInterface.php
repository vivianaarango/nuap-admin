<?php
namespace App\Repositories\Contracts;

use App\Models\Balance;

/**
 * Interface DbBalanceRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbBalanceRepositoryInterface
{
    /**
     * @param int $userID
     * @return Balance|null
     */
    public function findByUserID(int $userID): ?Balance;

    /**
     * @param int $balanceID
     * @return Balance
     */
    public function findByID(int $balanceID): Balance;
}

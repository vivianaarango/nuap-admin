<?php
namespace App\Repositories;

use App\Models\Balance;
use App\Models\BankAccount;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;

/**
 * Class DbBankAccountRepository
 * @package App\Repositories
 */
class DbBankAccountRepository implements DbBankAccountRepositoryInterface
{
    /**
     * @param int $userID
     * @return BankAccount
     */
    public function findByUserID(int $userID): BankAccount
    {
        return BankAccount::where('user_id', $userID)
            ->first();
    }

    /**
     * @param int $bankAccountID
     * @return BankAccount
     */
    public function findByID(int $bankAccountID): BankAccount
    {
        return BankAccount::findOrFail($bankAccountID);
    }
}

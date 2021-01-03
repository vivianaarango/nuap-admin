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
     * @return mixed
     */
    public function findByUserID(int $userID)
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

    /**
     * @param int $userID
     * @param int $account
     * @return mixed
     */
    public function findByUserIDAndAccount(int $userID, int $account)
    {
        return BankAccount::where('user_id', $userID)
            ->where('account', $account)
            ->first();
    }
}

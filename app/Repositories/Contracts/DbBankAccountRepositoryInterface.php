<?php
namespace App\Repositories\Contracts;

use App\Models\BankAccount;

/**
 * Interface DbBankAccountRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbBankAccountRepositoryInterface
{
    /**
     * @param int $userID
     * @return BankAccount
     */
    public function findByUserID(int $userID): BankAccount;

    /**
     * @param int $bankAccountID
     * @return BankAccount
     */
    public function findByID(int $bankAccountID): BankAccount;

    /**
     * @param int $userID
     * @param int $account
     * @return mixed
     */
    public function findByUserIDAndAccount(int $userID, int $account);
}

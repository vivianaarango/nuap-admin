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
     * @return mixed
     */
    public function findByUserID(int $userID);

    /**
     * @param int $bankAccountID
     * @return mixed
     */
    public function findByID(int $bankAccountID);

    /**
     * @param int $userID
     * @param int $account
     * @return mixed
     */
    public function findByUserIDAndAccount(int $userID, int $account);
}

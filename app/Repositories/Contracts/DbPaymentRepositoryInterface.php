<?php
namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DbDistributorRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbPaymentRepositoryInterface
{
    /**
     * @param int $userID
     * @return Payment|null|Collection
     */
    public function findByUserID(int $userID): Payment;

    /**
     * @param int $paymentID
     * @return Payment
     */
    public function findByID(int $paymentID): Payment;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findPendingByUserID(int $userID): Collection;
}

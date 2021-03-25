<?php
namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\DbPaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbPaymentRepository
 * @package App\Repositories
 */
class DbPaymentRepository implements DbPaymentRepositoryInterface
{
    /**
     * @param int $userID
     * @return Payment|null|Collection
     */
    public function findByUserID(int $userID): Payment
    {
        return Payment::where('user_id', $userID)
            ->first();
    }

    /**
     * @param int $paymentID
     * @return Payment
     */
    public function findByID(int $paymentID): Payment
    {
        return Payment::findOrFail($paymentID);
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findPendingByUserID(int $userID): Collection
    {
        return Payment::where('payments.user_id', $userID)
            ->where('payments.status', Payment::STATUS_PENDING)
            ->get();
    }
}

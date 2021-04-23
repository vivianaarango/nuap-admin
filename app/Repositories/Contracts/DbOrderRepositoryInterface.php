<?php
namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DbOrderRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbOrderRepositoryInterface
{
    /**
     * @param int $orderID
     * @param int $userID
     * @return Order|null|Collection
     */
    public function findByUserIDAndOrderUD(int $orderID, int $userID): Collection;

    /**
     * @param int $userID
     * @return Order|null|Collection
     */
    public function findByUserID(int $userID): Order;

    /**
     * @param int $orderID
     * @return Order
     */
    public function findByID(int $orderID): Order;

    /**
     * @param int $orderID
     * @return iterable
     */
    public function findProductsByOrderID(int $orderID): iterable;

    /**
     * @param int $clientID
     * @return Collection
     */
    public function findAllByClientID(int $clientID): Collection;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findAllByUserID(int $userID): Collection;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findInProgressByUserID(int $userID): Collection;

    /**
     * @param int $userID
     * @return float
     */
    public function findTodayOrdersDeliveredByUserID(int $userID): float;

    /**
     * @param int $userID
     * @return float
     */
    public function findLastWeekOrdersDeliveredByUserID(int $userID): float;

    /**
     * @param int $userID
     * @return float
     */
    public function findMonthDeliveredByUserID(int $userID): float;
}

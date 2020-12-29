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
}

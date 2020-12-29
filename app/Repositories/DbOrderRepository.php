<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbOrderRepository
 * @package App\Repositories
 */
class DbOrderRepository implements DbOrderRepositoryInterface
{
    /**
     * @param int $userID
     * @return Order|null|Collection
     */
    public function findByUserID(int $userID): Order
    {
        return Order::where('user_id', $userID)
            ->first();
    }

    /**
     * @param int $orderID
     * @return Order
     */
    public function findByID(int $orderID): Order
    {
        return Order::findOrFail($orderID);
    }

    /**
     * @param int $orderID
     * @return iterable
     */
    public function findProductsByOrderID(int $orderID): iterable
    {
        return OrderProduct::select('products.*', 'order_products.*')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->where('order_products.order_id', $orderID)
            ->orderBy('order_products.id', 'desc')
            ->get();
    }
}

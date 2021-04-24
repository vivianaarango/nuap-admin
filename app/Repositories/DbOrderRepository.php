<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbOrderRepository
 * @package App\Repositories
 */
class DbOrderRepository implements DbOrderRepositoryInterface
{
    /**
     * @param int $orderID
     * @param int $userID
     * @return null|Collection
     */
    public function findByUserIDAndOrderUD(int $orderID, int $userID): Collection
    {
        return Order::where('client_id', $userID)
            ->where('id', $orderID)
            ->where('status', Order::STATUS_INITIATED)
            ->get();
    }

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

    /**
     * @param int $clientID
     * @return Collection
     */
    public function findAllByClientID(int $clientID): Collection
    {
        return Order::select('orders.*', 'user_locations.address')
            ->where('client_id', $clientID)
            ->join('user_locations', 'user_locations.id', '=', 'orders.address_id')
            ->get();
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findAllByUserID(int $userID): Collection
    {
        return Order::select('orders.*', 'user_locations.address')
            ->where('orders.user_id', $userID)
            ->join('user_locations', 'user_locations.id', '=', 'orders.address_id')
            ->get();
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findInProgressByUserID(int $userID): Collection
    {
        return Order::where('user_id', $userID)
            ->where('orders.status', '<>', Order::STATUS_DELIVERED)
            ->where('orders.status', '<>', Order::STATUS_CANCEL)
            ->get();
    }

    /**
     * @param int $userID
     * @return float
     */
    public function findTodayOrdersDeliveredByUserID(int $userID): float
    {
        $year = (string) date('Y');
        $month = (string) date('m');
        $currentDay = (string) date('d');

        return Order::where('user_id', $userID)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereBetween('orders.created_at', [$year . '-' . $month . '-' . $currentDay . ' 00:00:00', $year . '-' . $month . '-' .$currentDay . ' 23:59:59'])
            ->sum('total');
    }

    /**
     * @param int $userID
     * @return float
     */
    public function findMonthDeliveredByUserID(int $userID): float
    {
        $year = (string) date('Y');
        $month = (string) date('m');

        $date = new DateTime();
        $date->modify('last day of this month');
        $lastDay = $date->format('d');

        return Order::where('user_id', $userID)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereBetween('orders.created_at', [$year . '-' . $month . '-' . 1 . ' 00:00:00', $year . '-' . $month . '-' .$lastDay . ' 23:59:59'])
            ->sum('total');
    }

    /**
     * @param int $userID
     * @return float
     */
    public function findLastWeekOrdersDeliveredByUserID(int $userID): float
    {
        $year = (string) date('Y');
        $month = (string) date('m');
        $currentDay = (string) date('d');

        $date = date('d-m-Y');
        $lastWeek = date('d', strtotime($date . '- 7 days'));

        return Order::where('user_id', $userID)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereBetween('orders.created_at', [$year . '-' . $month . '-' . $lastWeek . ' 00:00:00', $year . '-' . $month . '-' .$currentDay . ' 23:59:59'])
            ->sum('total');
    }
}

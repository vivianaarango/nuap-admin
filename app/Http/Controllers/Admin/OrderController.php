<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\IndexOrder;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class OrderController
 * @package App\Http\Controllers\Admin
 */
class OrderController extends Controller
{
    /**
     * @var DbOrderRepositoryInterface
     */
    private $dbOrderRepository;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * OrderController constructor.
     * @param DbOrderRepositoryInterface $dbOrderRepository
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     */
    public function __construct(
        DbOrderRepositoryInterface $dbOrderRepository,
        DbUsersRepositoryInterface $dbUserRepository,
        DbClientRepositoryInterface $dbClientRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository
    ) {
        $this->dbOrderRepository = $dbOrderRepository;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbClientRepository = $dbClientRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
    }

    /**
     * @param IndexOrder $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexOrder $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Order::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'orders.*'
                    )->where('orders.user_id', Session::get('user')->id)
                    ->orderBy('orders.status', 'asc')
                    ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'total_products', 'total_amount', 'delivery_amount', 'total_discount', 'total', 'status'],
                    ['id', 'total_products', 'total_amount','delivery_amount', 'total_discount', 'total', 'status']
                );

            foreach ($data as $item) {
                $item->total_amount = $this->formatCurrency($item->total_amount) . ' $';
                $item->delivery_amount = $this->formatCurrency($item->delivery_amount) . ' $';
                $item->total_discount = $this->formatCurrency($item->total_discount) . ' $';
                $item->total = $this->formatCurrency($item->total) . ' $';
            }

            if ($request->ajax()) {
                return [
                    'data' => $data,
                    'activation' => $user->role
                ];
            }

            return view('admin.orders.index', [
                'data' => $data,
                'activation' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Order $order
     * @return Factory|Application|RedirectResponse|Redirector|View
     */
    public function view(Order $order)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::DISTRIBUTOR_ROLE) {
            $order = $this->dbOrderRepository->findByID($order->id);
            $order->total_amount = $this->formatCurrency($order->total_amount) . ' $';
            $order->delivery_amount = $this->formatCurrency($order->delivery_amount) . ' $';
            $order->total_discount = $this->formatCurrency($order->total_discount) . ' $';
            $order->total = $this->formatCurrency($order->total) . ' $';

            $client = null;
            if ($order->client_type === User::USER_ROLE) {
                $client = $this->dbClientRepository->findByUserID($order->client_id);
            }

            if ($order->client_type === User::COMMERCE_ROLE) {
                $client = $this->dbCommerceRepository->findByUserID($order->client_id);
            }

            $user = $this->dbUserRepository->findByID($order->client_id);
            $products = $this->dbOrderRepository->findProductsByOrderID($order->id);
            foreach ($products as $item){
                $item->price = $this->formatCurrency($item->price) . ' $';;
            }

            $status = null;

            if ($order->status === Order::STATUS_ACCEPTED) {
                $status = Order::STATUS_ENLISTMENT;
            }

            if ($order->status === Order::STATUS_ENLISTMENT) {
                $status = Order::STATUS_CIRCULATION;
            }

            if ($order->status === Order::STATUS_CIRCULATION) {
                $status = Order::STATUS_DELIVERED;
            }

            return view('admin.orders.view', [
                'order' => $order,
                'client' => $client,
                'user' => $user,
                'products' => $products,
                'activation' => $userAdmin->role,
                'status' => $status
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function changeStatus(Request $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $order = $this->dbOrderRepository->findByID($request['order_id']);
            $status = $order->status;
            if ($status === Order::STATUS_ACCEPTED) {
                $order->status = Order::STATUS_ENLISTMENT;
            }

            if ($status === Order::STATUS_ENLISTMENT) {
                $order->status = Order::STATUS_CIRCULATION;
            }

            if ($status === Order::STATUS_CIRCULATION) {
                $order->status = Order::STATUS_DELIVERED;
            }

            if ($status === Order::STATUS_INITIATED) {
                $order->status = Order::STATUS_CANCEL;
            }

            $order->save();

            return redirect('/admin/order-list');
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = "COP"): string
    {
        $currencies['COP'] = array(0,',','.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}
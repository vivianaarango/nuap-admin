<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

/**
 * Class PaymentGatewayController
 * @package App\Http\Controllers\Admin
 */
class PaymentGatewayController extends Controller
{
    /**
     * @var DbOrderRepositoryInterface
     */
    private $dbOrderRepository;

    /**
     * PaymentGatewayController constructor.
     * @param DbOrderRepositoryInterface $dbOrderRepository
     */
    public function __construct(
        DbOrderRepositoryInterface $dbOrderRepository
    )
    {
        $this->dbOrderRepository = $dbOrderRepository;
    }

    /**
     * @param Request $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $name = 'NUAP PAGO PEDIDOS (';
        $description = '';
        $total = 0;
        $error = false;
        $orders = $request->orders;
        $userID = $request->user;

        $ordersIDs = '';
        $orders = array_unique($orders);
        foreach ($orders as $item) {
            $ordersIDs = sprintf('%s-', $item);
            $order = $this->dbOrderRepository->findByUserIDAndOrderUD($item, $userID);
            if (! count($order)) {
                $error = true;
                return view('admin.payments.payment-gateway', [
                    'error' => $error,
                ]);
            }

            $products = $this->dbOrderRepository->findProductsByOrderID($item);

            foreach ($products as $product) {
                $data = sprintf('%s x %d = %d - ', $product->name, $product->quantity, $product->quantity * $product->price);
                $description .= $data;
            }

            $name .= sprintf('%d - ', $order->first()->id);
            $total += $order->first()->total;
        }

        $description = substr($description, 0, -3);
        $name = substr($name, 0, -3). ')';
        return view('admin.payments.payment-gateway', [
            'key' => '25acf336d3d5bd50167eb5e21cb363e1',
            'amount' => $total,
            'name' => $name,
            'description' => $description,
            'currency' => 'cop',
            'country' => 'co',
            'test' => 'true',
            'external' => 'true',
            'response' => 'http://127.0.0.1:8000/payment-response?orders='.$ordersIDs,
            'confirmation' => '',
            'error' => $error
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|Application|View
     */
    public function response(Request $request): View
    {
        $reference = $request->input('ref_payco');
        $orders = explode('-', $request->input('orders'));

        foreach ($orders as $item) {
            if ($item != null){
                $order = $this->dbOrderRepository->findByID($item);
                $order->reference = $reference;
                $order->save();
            }
        }

        return view('admin.payments.payment-response', []);
    }
}

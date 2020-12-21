<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\IndexPayment;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbPaymentRepositoryInterface;
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
 * Class PaymentController
 * @package App\Http\Controllers\Admin
 */
class PaymentController extends Controller
{
    /**
     * @var DbBalanceRepositoryInterface
     */
    private $dbBalanceRepository;

    /**
     * @var DbBankAccountRepositoryInterface
     */
    private $dbBankAccountRepository;

    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbPaymentRepositoryInterface
     */
    private $dbPaymentRepository;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * PaymentController constructor.
     * @param DbBalanceRepositoryInterface $dbBalanceRepository
     * @param DbBankAccountRepositoryInterface $dbBankAccountRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbPaymentRepositoryInterface $dbPaymentRepository
     * @param DbUsersRepositoryInterface $dbUserRepository
     */
    public function __construct(
        DbBalanceRepositoryInterface $dbBalanceRepository,
        DbBankAccountRepositoryInterface $dbBankAccountRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbPaymentRepositoryInterface $dbPaymentRepository,
        DbUsersRepositoryInterface $dbUserRepository
    ) {
        $this->dbBalanceRepository = $dbBalanceRepository;
        $this->dbBankAccountRepository = $dbBankAccountRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbPaymentRepository = $dbPaymentRepository;
        $this->dbUserRepository = $dbUserRepository;
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $balance = $this->dbBalanceRepository->findByUserID($user->id);
            return view('admin.payments.create', [
                'activation' => $user->role,
                'balance' => $balance
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $account = $this->dbBankAccountRepository->findByUserIDAndAccount($user->id, $request['account']);
            if (is_null($account)) {
                $account = null;
                $account['user_id'] = $user->id;
                $account['user_type'] = $user->role;
                $account['owner_name'] = $request['owner_name'];
                $account['owner_document'] = $request['owner_document'];
                $account['account'] = $request['account'];
                $account['account_type'] = $request['account_type'];
                $account['bank'] = $request['bank'];
                $account = BankAccount::create($account);
            }

            $payment['user_id'] = $user->id;
            $payment['user_type'] = $user->role;
            $payment['account_id'] = $account->id;
            $payment['value'] = $request['value'];
            $payment['status'] = Payment::STATUS_PENDING;
            $payment['request_date'] = now();
            Payment::create($payment);

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/ticket-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }

            $balance = $this->dbBalanceRepository->findByUserID($user->id);
            return view('admin.payments.create', [
                'activation' => $user->role,
                'balance' => $balance
            ]);

        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param IndexPayment $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function adminList(IndexPayment $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Payment::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'bank_accounts.bank',
                        'users.email',
                        'payments.*'
                    )->join('users', 'users.id', '=', 'payments.user_id')
                    ->join('bank_accounts', 'bank_accounts.id', '=', 'payments.account_id')
                    ->where('users.status', User::STATUS_ACTIVE)
                    ->orderBy('payments.status', 'asc')
                    ->orderBy('request_date', 'asc')
                    ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'bank', 'value', 'request_date', 'payment_date', 'status', 'updated_at'],
                    ['id', 'email', 'bank', 'value', 'request_date', 'payment_date', 'status', 'updated_at']
                );

            foreach ($data as $item){
                if ($item->user_type === User::DISTRIBUTOR_ROLE){
                    $ticketUser =$this->dbDistributorRepository->findByUserID($item->user_id);
                    $item->email = $ticketUser->business_name;
                }

                if ($item->user_type === User::COMMERCE_ROLE){
                    $ticketUser =$this->dbCommerceRepository->findByUserID($item->user_id);
                    $item->email = $ticketUser->business_name;
                }
            }

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.payments.admin-index', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Payment $payment
     * @return Factory|Application|RedirectResponse|Redirector|View
     */
    public function view(Payment $payment)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            $payment = $this->dbPaymentRepository->findByID($payment->id);
            $account = $this->dbBankAccountRepository->findByID($payment->account_id);

            $user = null;
            if ($payment->user_type === User::DISTRIBUTOR_ROLE)
                $user = $this->dbDistributorRepository->findByUserID($payment->user_id);
            if ($payment->user_type === User::COMMERCE_ROLE)
                $user = $this->dbCommerceRepository->findByUserID($payment->user_id);

            $phone = $this->dbUserRepository->findByID($payment->user_id)->phone;
            return view('admin.payments.view', [
                'payment' => $payment,
                'activation' => $userAdmin->role,
                'account' => $account,
                'user' => $user,
                'phone' => $phone
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function uploadVoucher(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $voucherPath = 'voucher/'.$request['phone'];
            if (!is_dir($voucherPath)) {
                mkdir($voucherPath, 0777, true);
            }

            $voucher = $_FILES['voucher'];

            $urlVoucher = null;
            if ($voucher['name'] != '') {
                $ext = pathinfo($voucher['name'], PATHINFO_EXTENSION);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $urlVoucher = "{$voucherPath}/".substr(str_shuffle($permitted_chars), 0, 16).".{$ext}";
                $destinationRoute = $urlVoucher;
                move_uploaded_file($voucher['tmp_name'], $destinationRoute);
            }

            $payment = $this->dbPaymentRepository->findByID($request['payment_id']);
            $payment->voucher = $urlVoucher;
            $payment->payment_date = now();
            $payment->status = Payment::STATUS_APPROVED;
            $payment->save();

            $balance = $this->dbBalanceRepository->findByUserID($payment->user_id);
            $balance->balance = $balance->balance - $request['value'];
            $balance->paid_out = $balance->paid_out + $request['value'];
            $balance->save();

            return redirect('/admin/payment-admin-list');
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function rejectedPayment(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $payment = $this->dbPaymentRepository->findByID($request['payment_id']);
            $payment->status = Payment::STATUS_REJECTED;
            $payment->save();

            return redirect('/admin/payment-admin-list');
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param IndexPayment $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexPayment $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Payment::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'bank_accounts.bank',
                        'payments.*'
                    )->join('users', 'users.id', '=', 'payments.user_id')
                        ->join('bank_accounts', 'bank_accounts.id', '=', 'payments.account_id')
                        ->where('payments.user_id', Session::get('user')->id)
                        ->where('users.status', User::STATUS_ACTIVE)
                        ->orderBy('payments.status', 'asc')
                        ->orderBy('request_date', 'asc')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'bank', 'value', 'request_date', 'payment_date', 'status', 'updated_at'],
                    ['id', 'bank', 'value', 'request_date', 'payment_date', 'status', 'updated_at']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.payments.index', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Payment $payment
     * @return Factory|Application|RedirectResponse|Redirector|View
     */
    public function detail(Payment $payment)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::DISTRIBUTOR_ROLE) {
            $payment = $this->dbPaymentRepository->findByID($payment->id);
            $account = $this->dbBankAccountRepository->findByID($payment->account_id);

            $user = null;
            if ($payment->user_type === User::DISTRIBUTOR_ROLE)
                $user = $this->dbDistributorRepository->findByUserID($payment->user_id);
            if ($payment->user_type === User::COMMERCE_ROLE)
                $user = $this->dbCommerceRepository->findByUserID($payment->user_id);

            return view('admin.payments.detail', [
                'payment' => $payment,
                'activation' => $userAdmin->role,
                'account' => $account,
                'user' => $user
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function cancelPayment(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $payment = $this->dbPaymentRepository->findByID($request['payment_id']);
            $payment->status = Payment::STATUS_CANCEL;
            $payment->save();

            return redirect('/admin/payment-list');
        }

        return redirect('/admin/user-session');
    }
}
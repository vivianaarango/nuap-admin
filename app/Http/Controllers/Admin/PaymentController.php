<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;
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
     * PaymentController constructor.
     * @param DbBalanceRepositoryInterface $dbBalanceRepository
     * @param DbBankAccountRepositoryInterface $dbBankAccountRepository
     */
    public function __construct(
        DbBalanceRepositoryInterface $dbBalanceRepository,
        DbBankAccountRepositoryInterface $dbBankAccountRepository
    ) {
        $this->dbBalanceRepository = $dbBalanceRepository;
        $this->dbBankAccountRepository = $dbBankAccountRepository;
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
}
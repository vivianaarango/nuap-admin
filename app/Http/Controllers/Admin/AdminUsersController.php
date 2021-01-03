<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\CreateAdminUsers;
use App\Models\AdminUser;
use App\Models\BankAccount;
use App\Models\Config;
use App\Models\User;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class AdminUsersController
 * @package App\Http\Controllers\Admin
 */
class AdminUsersController extends Controller
{
    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var DbAdminUsersRepositoryInterface
     */
    private $dbAdminUserRepository;

    /**
     * @var DbBankAccountRepositoryInterface
     */
    private $dbBankAccountRepository;

    /**
     * AdminUsersController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbAdminUsersRepositoryInterface $dbAdminUserRepository
     * @param DbBankAccountRepositoryInterface $dbBankAccountRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbAdminUsersRepositoryInterface $dbAdminUserRepository,
        DbBankAccountRepositoryInterface $dbBankAccountRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbAdminUserRepository = $dbAdminUserRepository;
        $this->dbBankAccountRepository = $dbBankAccountRepository;
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.admin-users.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateAdminUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateAdminUsers $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            AdminUser::create($data);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/admin-users-create'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/admin-users-create');
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function createConfig()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $config = Config::first();
            $account = null;

            if (!is_null($config->account_id)) {
                $account = $this->dbBankAccountRepository->findByID($config->account_id);
            }

            return view('admin.admin-users.config', [
                'config' => $config,
                'account' => $account,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function storeConfig(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            if ($request['config_id']) {
                $config = Config::where('id', $request['config_id'])->first();
                $config->shipping_cost = $request['shipping_cost'];
                $config->distance = $request['distance'];
                $config->save();
            } else {
                $data['shipping_cost'] = $request['shipping_cost'];
                $data['distance'] = $request['distance'];
                $config = Config::create($data);
            }

            $account = null;
            if (!is_null($config->account_id)) {
                $account = $this->dbBankAccountRepository->findByID($config->account_id);
            }

            if (is_null($account)) {
                $account['user_id'] = $user->id;
                $account['user_type'] = $user->role;
                $account['owner_name'] = $request['owner_name'];
                $account['owner_document'] = $request['owner_document'];
                $account['owner_document_type'] = $request['owner_document_type'];
                $account['account'] = $request['account'];
                $account['account_type'] = $request['account_type'];
                $account['bank'] = $request['bank'];
                $account['status'] = BankAccount::ACCOUNT_ACTIVE;
                $documents = 'documents/'.$user->phone;
                if (!is_dir($documents)) {
                    mkdir($documents, 0777, true);
                }

                $urlCertificate = null;
                $certificate = $_FILES['certificate'];
                if ($certificate['name'] != '') {
                    $ext = pathinfo($certificate['name'], PATHINFO_EXTENSION);
                    $urlCertificate = "{$documents}/7.Certificado Bancario.{$ext}";
                    $destinationRoute = $urlCertificate;
                    move_uploaded_file($certificate['tmp_name'], $destinationRoute);
                }
                $account['certificate'] = $urlCertificate;
                $bankAccount = BankAccount::create($account);
                $config->account_id = $bankAccount->id;
                $config->save();
            } else {
                $account['owner_name'] = $request['owner_name'];
                $account['owner_document'] = $request['owner_document'];
                $account['owner_document_type'] = isset($request['owner_document_type']) ? $request['owner_document_type'] : $account->owner_document_type;
                $account['account'] = $request['account'];
                $account['account_type'] = isset($request['account_type']) ? $request['account_type'] : $account->account_type;
                $account['bank'] = isset($request['bank']) ? $request['bank'] : $account->bank;
                $account['status'] = BankAccount::ACCOUNT_INACTIVE;
                $documents = 'documents/'.$user->phone;
                if (!is_dir($documents)) {
                    mkdir($documents, 0777, true);
                }

                $urlCertificate = null;
                $certificate = $_FILES['certificate'];
                if ($certificate['name'] != '') {
                    $ext = pathinfo($certificate['name'], PATHINFO_EXTENSION);
                    $urlCertificate = "{$documents}/7.Certificado Bancario.{$ext}";
                    $destinationRoute = $urlCertificate;
                    move_uploaded_file($certificate['tmp_name'], $destinationRoute);
                }
                $account['certificate'] = !is_null($urlCertificate) ? $urlCertificate : $account->certificate;
                $account->save();
                $config->account_id = $account->id;
                $config->save();
            }
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/config-create'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/config-create');
    }
}

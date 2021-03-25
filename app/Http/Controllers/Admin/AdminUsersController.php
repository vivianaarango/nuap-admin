<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\CreateAdminUsers;
use App\Http\Requests\Admin\AdminUser\IndexAdmin;
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
use Brackets\AdminListing\Facades\AdminListing;
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
     * @var string
     */
    private $dateToSearch;

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
     * @param IndexAdmin $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexAdmin $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $this->dateToSearch = date("Y-m-d");
            $days = $request['days'];
            if ($request->ajax()) {
                $url = url()->previous();
                $parts = parse_url($url);
                if (isset($parts['query'])){
                    parse_str($parts['query'], $query);
                    $days = $query['days'] ?? null;
                }
            }

            if (!is_null($days) && $days != 0) {
                $this->dateToSearch = date("Y-m-d",strtotime($this->dateToSearch." - ".$days." days"));
            } else {
                $this->dateToSearch = date("Y-m-d",strtotime($this->dateToSearch." + 1 days"));
            }

            $data = AdminListing::create(User::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'users.*',
                        'admin_users.name',
                        'admin_users.last_name',
                        'admin_users.identity_number',
                        'admin_users.position'
                    )->where('role', User::ADMIN_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('admin_users', 'users.id', '=', 'admin_users.user_id')
                        ->orderBy('id', 'desc');;
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'phone', 'status', 'last_logged_in'],
                    ['id', 'email', 'phone', 'status', 'last_logged_in']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.admin-users.index', [
                'data' => $data,
                'activation' => $user->name,
                'days' => $days,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => ['nullable', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'country_code' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
        ]);

        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $user = $this->dbUserRepository->findByID($request['user_id']);
            if ($request['phone'] != $user->phone) {
                $phoneValidated = false;
            }
            $this->dbUserRepository->updateUser(
                $request['user_id'],
                $request['email'],
                $request['country_code'],
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );
            $this->dbAdminUserRepository->updateAdmin(
                $request['client_id'],
                $request['user_id'],
                $request['position'],
                $request['name'],
                $request['last_name'],
                $request['identity_number']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/validate-session');
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.admin-users.create', [
                'activation' => $user->name,
                'role' => $user->role,
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

        return redirect('admin/list');
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
                'activation' => $user->name,
                'role' => $user->role,
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

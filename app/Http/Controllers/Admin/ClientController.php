<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Client\CreateClient;
use App\Http\Requests\Admin\Client\IndexClient;
use App\Http\Requests\Admin\Commerce\CreateCommerce;
use App\Models\Client;
use App\Models\User;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class ClientController
 * @package App\Http\Controllers\Admin
 */
class ClientController extends Controller
{
    /**
     * @var string
     */
    private $dateToSearch;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * ClientController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbClientRepository = $dbClientRepository;
    }

    /**
     * @param IndexClient $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexClient $request)
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

            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(User::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'users.*',
                        'clients.name',
                        'clients.last_name',
                        'clients.identity_number'
                    )->where('role', User::USER_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('clients', 'users.id', '=', 'clients.user_id')
                        ->orderBy('id', 'desc');;
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'phone', 'status', 'last_logged_in'],
                    ['id', 'email', 'phone', 'status', 'last_logged_in']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.clients.index', [
                'data' => $data,
                'activation' => $user->role,
                'days' => $days
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.clients.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateClient $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateClient $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            Client::create($data);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/client-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/client-list');
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    /*public function update(Request $request)
    {
        $this->validate($request, [
            'email' => ['nullable', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'business_name' => ['required', 'string'],
            'nit' => ['required', 'string'],
            'second_phone' => ['required', 'string'],
            'commission' => ['numeric', 'min:0.0','max:100.00'],
            'type' => ['required', 'string'],
            'name_legal_representative' => ['required', 'string'],
            'cc_legal_representative' => ['required', 'string'],
            'contact_legal_representative' => ['required', 'string']
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
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );
            $this->dbCommerceRepository->updateCommerce(
                $request['commerce_id'],
                $request['user_id'],
                $request['business_name'],
                $request['nit'],
                $request['second_phone'],
                $request['commission'],
                $request['type'],
                $request['name_legal_representative'],
                $request['cc_legal_representative'],
                $request['contact_legal_representative']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/commerce-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/validate-session');
    }*/
}

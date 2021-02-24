<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Commerce\CreateCommerce;
use App\Http\Requests\Admin\Commerce\IndexCommerce;
use App\Http\Requests\Admin\Distributor\IndexCommission;
use App\Models\Commerce;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class CommerceController
 * @package App\Http\Controllers\Admin
 */
class CommerceController extends Controller
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
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * CommerceController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
    }

    /**
     * @param IndexCommerce $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexCommerce $request)
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
                        'commerces.business_name',
                        'commerces.type',
                        'commerces.commission',
                        'commerces.name_legal_representative'
                    )->where('role', User::COMMERCE_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('commerces', 'users.id', '=', 'commerces.user_id')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'phone', 'status', 'last_logged_in'],
                    ['id', 'email', 'phone', 'status', 'last_logged_in']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.commerces.index', [
                'data' => $data,
                'activation' => $user->name,
                'role' => $user->role,
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
            return view('admin.commerces.create', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateCommerce $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateCommerce $request)
    {
        $countryCode = $request['country_code'];
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['country_code'] = $countryCode;
            Commerce::create($data);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/commerce-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/commerce-list');
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
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
            'country_code' => ['required', 'string'],
            'country_code_user' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'business_name' => ['required', 'string'],
            'nit' => ['required', 'string'],
            'second_phone' => ['required', 'string'],
            'commission' => ['numeric', 'min:0.0','max:100.00'],
            'type' => ['required', 'string'],
            'name_legal_representative' => ['required', 'string'],
            'cc_legal_representative' => ['required', 'string'],
            'country_code_legal_representative' => ['required', 'string'],
            'contact_legal_representative' => ['required', 'string'],
            'shipping_cost' => ['nullable', 'numeric'],
            'distance' => ['nullable', 'numeric']
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
                $request['country_code_user'],
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );

            $this->dbCommerceRepository->updateCommerce(
                $request['commerce_id'],
                $request['user_id'],
                $request['business_name'],
                $request['nit'],
                $request['country_code'],
                $request['second_phone'],
                $request['commission'],
                $request['type'],
                $request['name_legal_representative'],
                $request['cc_legal_representative'],
                $request['country_code_legal_representative'],
                $request['contact_legal_representative'],
                $request['shipping_cost'],
                $request['distance']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/commerce-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/validate-session');
    }

    /**
     * @param IndexCommission $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function editCommission(IndexCommission $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Commerce::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'commerces.*',
                        'commerces.commission',
                        'commerces.name_legal_representative'
                    )
                        ->join('users', 'users.id', '=', 'commerces.user_id')
                        ->where('users.role', User::COMMERCE_ROLE)
                        ->where('users.status', User::STATUS_ACTIVE)
                        ->orderBy('commerces.id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'business_name', 'commission', 'name_legal_representative'],
                    ['id', 'business_name', 'commission', 'name_legal_representative']
                );

            if ($request->ajax()) {
                if ($request->has('bulk')) {
                    return [
                        'bulkItems' => $data->pluck('id')
                    ];
                }
                return ['data' => $data];
            }

            return view('admin.commerces.commission', [
                'data' => $data,
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function updateCommission(Request $request)
    {
        $newCommission = $request->data['commission'];
        $idsDistributors = $request->data['ids'];

        foreach ($idsDistributors as $item) {
            $this->dbCommerceRepository->updateCommission($item, $newCommission);
        }

        if ($request->ajax()) {
            return response(['message' => trans('Se ha actualizado la comisión correctamente')]);
        }

        return redirect()->back();
    }
}

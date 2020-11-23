<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Commerce\CreateCommerce;
use App\Http\Requests\Admin\Commerce\IndexCommerce;
use App\Models\Commerce;
use App\Models\User;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
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
     * @var
     */
    private $dbCommerceRepository;

    /**
     * CommeceController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository
        //DbDistributorRepositoryInterface $dbDistributorRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        //$this->dbDistributorRepository = $dbDistributorRepository;
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

            if (!is_null($days)){
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
                        'commerces.city',
                        'commerces.commission',
                        'commerces.name_legal_representative'
                    )->where('role', User::COMMERCE_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('commerces', 'users.id', '=', 'commerces.user_id');
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
            return view('admin.commerces.create', [
                'activation' => $user->role
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
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
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
    /*public function update(Request $request)
    {
        $this->validate($request, [
            'email' => ['nullable', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'business_name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'location' => ['required', 'string'],
            'neighborhood' => ['required', 'string'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
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
            $user = $this->dbUserRepository->updateUser(
                $request['user_id'],
                $request['email'],
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );
            $this->dbDistributorRepository->updateDistributor(
                $request['distributor_id'],
                $request['user_id'],
                $request['business_name'],
                $request['city'],
                $request['location'],
                $request['neighborhood'],
                $request['address'],
                $request['latitude'],
                $request['longitude'],
                $request['commission'],
                $request['type'],
                $request['name_legal_representative'],
                $request['cc_legal_representative'],
                $request['contact_legal_representative']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/distributor-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/validate-session');
    }*/
}

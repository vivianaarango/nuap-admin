<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\CreateDistributor;
use App\Http\Requests\Admin\Distributor\IndexDistributor;
use App\Models\Distributor;
use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class DistributorController
 * @package App\Http\Controllers\Admin
 */
class DistributorController extends Controller
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
     * UsersController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
    }

    /**
     * @param IndexDistributor $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexDistributor $request)
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
                            'distributors.business_name',
                            'distributors.city',
                            'distributors.commission',
                            'distributors.name_legal_representative'
                        )->where('role', User::DISTRIBUTOR_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('distributors', 'users.id', '=', 'distributors.user_id');
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'phone', 'status', 'last_logged_in'],
                    ['id', 'email', 'phone', 'status', 'last_logged_in']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.distributors.index', [
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
            return view('admin.distributors.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateDistributor $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateDistributor $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            Distributor::create($data);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/distributor-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/distributor-list');
    }
}

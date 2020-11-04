<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUsers;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\User;
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
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{
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
     * @param LoginUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function __invoke(LoginUsers $request)
    {
        $password = md5($request['password']);
        $user = $this->dbUserRepository->findUserByEmailAndPassword($request['email'], $password);

        if (!count($user)) {
            return redirect()->back();
        }

        /* @var User $user */
        $user = $user[0];

        if ($user->role == User::ADMIN_ROLE) {
            Session::put('user', $user);
            return redirect('admin/user-wholesaler-list');
        }

        if ($user->role == User::WHOLESALER_ROLE) {
            dd('pailalalalala');
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        Session::remove('user');
        return redirect('admin/login');
    }

    /**
     * @param Request $request
     * @return array|Factory|Application|RedirectResponse|View
     */
    public function listWholesaler(Request $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(User::class)
                ->modifyQuery(function($query) {
                    $query->where('role', User::WHOLESALER_ROLE);
                })->processRequestAndGet(
                    $request,
                    ['id', 'name', 'lastname', 'email', 'phone', 'commission', 'discount', 'status', 'last_logged_in'],
                    ['id', 'last_logged_in']
                );

            return view('admin.users.index', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function create(Request $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.users.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * @param CreateUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateUsers $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $sanitized = $request->getModifiedData();
            User::create($sanitized);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/user-wholesaler-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/user-wholesaler-list');
    }
}

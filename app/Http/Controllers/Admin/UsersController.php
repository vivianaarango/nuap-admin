<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUsers;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
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
            return redirect('admin/login');
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
     * @return array|Application|RedirectResponse|Redirector
     */
    public function validateSession(Request $request)
    {
        $user = Session::get('user');
        if (!is_null($user)) {
            return redirect('admin/user-wholesaler-list');
        } else {
            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/login'),
                    'message' => 'session not exist'
                ];
            } else {
                return redirect('admin/login');
            }
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logout()
    {
        Session::remove('user');
        return redirect('/admin/user-session');
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.users.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
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

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUsers;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Http\Requests\Admin\Users\UpdateUsers;
use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            $this->dbUserRepository->updateLastLogin($user->id, now());
            return redirect('admin/user-wholesaler-list');
        }

        if ($user->role == User::WHOLESALER_ROLE) {
            dd('En desarrollo');
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

    /**
     * @param User $user
     * @return Response|Factory|Application|View
     */
    public function edit(User $user)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            $user->password = null;
            return view('admin.users.edit', [
                'user' => $user,
                'activation' => $userAdmin->role,
                'showFields' => true
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param UpdateUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function update(UpdateUsers $request)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $this->dbUserRepository->updateUser(
                $request['id'],
                $request['name'],
                $request['lastname'],
                $request['identity_type'],
                $request['identity_number'],
                $request['phone'],
                $request['email'],
                $request['role'],
                is_null($request['commission']) ? null : $request['commission'],
                is_null($request['discount']) ? null : $request['discount'],
                is_null($request['password']) ? null : md5($request['password'])
            );
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/user-wholesaler-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/user-wholesaler-list');
    }

    /**
     * @param User $user
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function delete(User $user)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $deleteValidation = $this->dbUserRepository->deleteUser($user->id);
            if ($deleteValidation) {
                return response(['redirect' => url('admin/user-wholesaler-list')]);
            } else {
                return response(['message' => 'Ha ocurrido un error.']);
            }
        } else {
            return redirect('admin/user-session');
        }
    }

    /**
     * @param User $user
     * @return Response|Factory|Application|View
     */
    public function changeStatus(User $user)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            if ($user->status == User::STATUS_ACTIVE) {
                $this->dbUserRepository->changeStatus($user->id, User::STATUS_INACTIVE);
            } else {
                $this->dbUserRepository->changeStatus($user->id, User::STATUS_ACTIVE);
            }

            return response(['redirect' => url('admin/user-wholesaler-list')]);
        } else {
            return redirect('/admin/user-session');
        }
    }
}

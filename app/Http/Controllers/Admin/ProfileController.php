<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\User;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
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
 * Class ProfileController
 * @package App\Http\Controllers\Admin
 */
class ProfileController extends Controller
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
     * ProfileController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbAdminUsersRepositoryInterface $dbAdminUserRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbAdminUsersRepositoryInterface $dbAdminUserRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbAdminUserRepository = $dbAdminUserRepository;
    }

    /**
     * @return Factory|Application|View
     */
    public function edit()
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @var $userInfo AdminUser */
            $userInfo = $this->dbAdminUserRepository->findByUserID($user->id);
            $data = [
                "user_id" => $user->id,
                "email" => $user->email,
                "phone" => $user->phone,
                "name" => $userInfo[0]->name,
                "last_name" => $userInfo[0]->last_name
            ];
            return view('admin.profile.edit-profile', [
                'user' => json_encode($data),
                'activation' => $user->role
            ]);
        } else {
            return redirect('admin/login');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
        ]);

        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            if ($request['phone'] != $user->phone){
                $phoneValidated = false;
            }
            $user = $this->dbUserRepository->updateProfileUser(
                $request['user_id'],
                $request['phone'],
                $request['email'],
                isset($phoneValidated) ? $phoneValidated : true
            );
            $this->dbAdminUserRepository->updateProfileAdminUser(
                $request['user_id'],
                $request['name'],
                $request['last_name']
            );

            Session::put('user', $user);

            if ($request->ajax()) {
                return [
                    'notify' =>
                        [
                            'type' => 'success',
                            'message' => 'Se ha actualizado exitosamente tu perfil',
                            'title' => 'Editar perfil',
                            'redirect' => url('admin/edit-profile')
                        ]
                ];
            }
        }

        return redirect('admin/edit-profle');
    }

    /**
     * @return Factory|Application|Response|View
     */
    public function editPassword()
    {
        $user = Session::get('user');

        if (!is_null($user)) {
            $user->password = null;
            return view('admin.profile.edit-password', [
                'user' => $user,
                'activation' => $user->role
            ]);
        } else {
            return redirect('admin/login');
        }
    }


    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'
            ]
        ]);

        $user = $this->dbUserRepository->updatePassword($request['id'], md5($request['password']));
        Session::put('user', $user);

        if ($request->ajax()) {
            return [
                'notify' =>
                    [
                    'type' => 'success',
                    'message' => 'Se ha actualizado exitosamente tu contraseña',
                    'title' => 'Cambio de cotraseña',
                    'redirect' => url('admin/edit-profile')
                ]
            ];
        }

        return redirect('admin/edit-password');
    }
}

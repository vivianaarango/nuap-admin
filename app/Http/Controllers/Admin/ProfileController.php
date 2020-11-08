<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * ProfileController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
    }

    /**
     * @return Factory|Application|View
     */
    public function edit()
    {
        $user = Session::get('user');
        if (!is_null($user)) {
            return view('admin.profile.edit-profile', [
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
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string']
        ]);

        $user = $this->dbUserRepository->updateUser(
            $request['id'],
            $request['name'],
            $request['lastname'],
            $request['phone'],
            $request['email']
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

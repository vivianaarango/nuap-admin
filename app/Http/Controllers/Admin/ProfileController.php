<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\User;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
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
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * ProfileController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbAdminUsersRepositoryInterface $dbAdminUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbAdminUsersRepositoryInterface $dbAdminUserRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbAdminUserRepository = $dbAdminUserRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
    }

    /**
     * @return Factory|Application|View
     */
    public function edit()
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $userInfo = $this->dbAdminUserRepository->findByUserID($user->id);
            $data = [
                "user_id" => $user->id,
                "email" => $user->email,
                "phone" => $user->phone,
                "name" => $userInfo->name,
                "last_name" => $userInfo->last_name
            ];
            return view('admin.profile.edit-profile', [
                'image_url' => $userInfo->image_url,
                'user_id' => $data['user_id'],
                'user' => json_encode($data),
                'activation' => $user->name,
                'role' => $user->role,
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
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
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

    /**
     * @return Factory|Application|View
     */
    public function editDistributor()
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $distributor = $this->dbDistributorRepository->findByUserID($user->id);
            $data = [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'business_name' => $distributor->business_name,
                'nit' => $distributor->nit
            ];
            return view('admin.profile.edit-profile-dist', [
                'user' => json_encode($data),
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function updateImage(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $imgProfile = 'admin/'.$user->id;
            if (!is_dir($imgProfile)) {
                mkdir($imgProfile, 0777, true);
            }

            $image = $_FILES['image'];
            $urlImage = null;
            if ($image['name'] != '') {
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $urlImage = "{$imgProfile}/profile.{$ext}";
                $destinationRoute = $urlImage;
                move_uploaded_file($image['tmp_name'], $destinationRoute);
            }
            $admin = $this->dbAdminUserRepository->findByUserID($user->id);
            $admin->image_url = $urlImage;
            $admin->save();

            return redirect('admin/edit-profile');
        }

        return redirect('/admin/user-session');
    }
}

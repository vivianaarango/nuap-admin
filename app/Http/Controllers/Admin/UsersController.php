<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
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
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * UsersController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
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
            return redirect('admin/distributor-list');
        }

        if ($user->role == User::DISTRIBUTOR_ROLE) {
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

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return redirect('admin/distributor-list');
        }

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            dd("Desarrollo");
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/login'),
                'message' => 'session not exist'
            ];
        } else {
            return redirect('admin/login');
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
     * @param User $user
     * @return Response|Factory|Application|View
     */
    public function edit(User $user)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            $data = [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => null
            ];

            if ($user->role == User::DISTRIBUTOR_ROLE) {
                $distributor = $this->dbDistributorRepository->findByUserID($user->id);
                $data['distributor_id'] = $distributor->id;
                $data['business_name'] = $distributor->business_name;
                $data['city'] = $distributor->city;
                $data['location'] = $distributor->location;
                $data['neighborhood'] = $distributor->neighborhood;
                $data['address'] = $distributor->address;
                $data['latitude'] = $distributor->latitude;
                $data['longitude'] = $distributor->longitude;
                $data['commission'] = $distributor->commission;
                $data['type'] = $distributor->type;
                $data['name_legal_representative'] = $distributor->name_legal_representative;
                $data['cc_legal_representative'] = $distributor->cc_legal_representative;
                $data['contact_legal_representative'] = $distributor->contact_legal_representative;

                return view('admin.distributors.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->role,
                    'url' => $distributor->resource_url,
                    'business_name' => $distributor->business_name
                ]);
            }

            if ($user->role == User::COMMERCE_ROLE) {
                $commerce = $this->dbCommerceRepository->findByUserID($user->id);
                $data['commerce_id'] = $commerce->id;
                $data['business_name'] = $commerce->business_name;
                $data['city'] = $commerce->city;
                $data['location'] = $commerce->location;
                $data['neighborhood'] = $commerce->neighborhood;
                $data['address'] = $commerce->address;
                $data['latitude'] = $commerce->latitude;
                $data['longitude'] = $commerce->longitude;
                $data['commission'] = $commerce->commission;
                $data['type'] = $commerce->type;
                $data['name_legal_representative'] = $commerce->name_legal_representative;
                $data['cc_legal_representative'] = $commerce->cc_legal_representative;
                $data['contact_legal_representative'] = $commerce->contact_legal_representative;

                return view('admin.commerces.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->role,
                    'url' => $commerce->resource_url,
                    'business_name' => $commerce->business_name
                ]);
            }

        } else {
            return redirect('/admin/user-session');
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param User $user
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function delete(User $user)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $this->dbUserRepository->deleteUser($user->id);
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

            if ($user->role == User::DISTRIBUTOR_ROLE) {
                return response(['redirect' => url('admin/distributor-list')]);
            }

            if ($user->role == User::COMMERCE_ROLE) {
                return response(['redirect' => url('admin/commerce-list')]);
            }

            return response(['redirect' => url('admin/distributor-list')]);
        } else {
            return redirect('/admin/user-session');
        }
    }
}

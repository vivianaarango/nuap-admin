<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUserLocation;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\User;
use App\Models\UserLocation;
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
        Session::put('user', $user);
        $this->dbUserRepository->updateLastLogin($user->id, now());

        if ($user->role == User::ADMIN_ROLE) {
            return redirect('admin/distributor-list');
        }

        if ($user->role == User::DISTRIBUTOR_ROLE) {
            return redirect('/admin/edit-profile-distributor');
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
            return redirect('/admin/edit-profile-distributor');
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
                $data['nit'] = $distributor->nit;
                $data['second_phone'] = $distributor->second_phone;
                $data['commission'] = $distributor->commission;
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
                $data['nit'] = $commerce->nit;
                $data['second_phone'] = $commerce->second_phone;
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

    /**
     * @param User $user
     * @return Response|Factory|Application|View
     */
    public function location(User $user)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            return view('admin.users.add-location', [
                'user' => ($user),
                'activation' => $userAdmin->role
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param CreateUserLocation $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function storeLocation(CreateUserLocation $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            UserLocation::create($data);

            if ($request->role == User::COMMERCE_ROLE){
                return redirect('admin/commerce-list');
            }
            if ($request->role == User::DISTRIBUTOR_ROLE){
                return redirect('admin/distributor-list');
            }
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param User $user
     * @return Response|Factory|Application|View
     */
    public function document(User $user)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            return view('admin.users.add-documents', [
                'user' => ($user),
                'activation' => $userAdmin->role
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function storeDocuments(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $documents = 'documents/'.$request->phone;
            if (!is_dir($documents)) {
                mkdir($documents, 0777);
            }

            $rut = (isset($_FILES['rut'])) ? $_FILES['rut'] : null;
            $commerceRoom = (isset($_FILES['commerce_room'])) ? $_FILES['commerce_room'] : null;
            $ccLegalRepresentative = (isset($_FILES['cc_legal_representative'])) ? $_FILES['cc_legal_representative'] : null;
            $establishmentImage = (isset($_FILES['establishment_image'])) ? $_FILES['establishment_image'] : null;
            $interiorImage = (isset($_FILES['interior_image'])) ? $_FILES['interior_image'] : null;
            $contract = (isset($_FILES['contract'])) ? $_FILES['contract'] : null;

            if ($rut) {
                $ext = pathinfo($rut['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/1.Rut.{$ext}";
                move_uploaded_file($rut['tmp_name'], $destinationRoute);
            }

            if ($commerceRoom) {
                $ext = pathinfo($commerceRoom['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/2.Camara de comercio.{$ext}";
                move_uploaded_file($commerceRoom['tmp_name'], $destinationRoute);
            }

            if ($ccLegalRepresentative) {
                $ext = pathinfo($ccLegalRepresentative['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/3.Cedula del representante legal.{$ext}";
                move_uploaded_file($ccLegalRepresentative['tmp_name'], $destinationRoute);
            }

            if ($establishmentImage) {
                $ext = pathinfo($establishmentImage['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/4.Foto del establecimiento.{$ext}";
                move_uploaded_file($establishmentImage['tmp_name'], $destinationRoute);
            }

            if ($interiorImage) {
                $ext = pathinfo($interiorImage['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/5.Foto estanteria, caja, bodega.{$ext}";
                move_uploaded_file($interiorImage['tmp_name'], $destinationRoute);
            }

            if ($contract) {
                $ext = pathinfo($contract['name'], PATHINFO_EXTENSION);
                $destinationRoute = "{$documents}/6.Contrato.{$ext}";
                move_uploaded_file($contract['tmp_name'], $destinationRoute);
            }

            if ($request->role == User::COMMERCE_ROLE){
                return redirect('admin/commerce-list');
            }
            if ($request->role == User::DISTRIBUTOR_ROLE){
                return redirect('admin/distributor-list');
            }
        }

        return redirect('/admin/user-session');
    }
}

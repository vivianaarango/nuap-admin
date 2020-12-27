<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUserLocation;
use App\Http\Requests\Admin\Users\IndexUserLocation;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\User;
use App\Models\UserLocation;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
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
     * @var User
     */
    private $user;

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
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * UsersController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbClientRepository = $dbClientRepository;
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
            return redirect('/admin/product-distributor-list');
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
            return redirect('/admin/product-distributor-list');
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

            if ($user->role == User::USER_ROLE) {
                $client = $this->dbClientRepository->findByUserID($user->id);
                $data['client_id'] = $client->id;
                $data['name'] = $client->name;
                $data['last_name'] = $client->last_name;
                $data['identity_number'] = $client->identity_number;

                return view('admin.clients.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->role,
                    'url' => $client->resource_url,
                    'business_name' => $client->name
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

            if ($user->role == User::USER_ROLE) {
                return response(['redirect' => url('admin/client-list')]);
            }

            return response(['redirect' => url('admin/user-session')]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param User $user
     * @param IndexUserLocation $request
     * @return Response|Factory|Application|View
     */
    public function location(IndexUserLocation $request, User $user)
    {
        $userAdmin = Session::get('user');
        $this->user = $user;

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(UserLocation::class)
                ->modifyQuery(function($query) {
                    $query->where('user_id', $this->user->id)
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'city', 'location', 'neighborhood', 'address'],
                    ['id', 'city', 'location', 'neighborhood', 'address']
                );

            return view('admin.users.add-location', [
                'data' => $data,
                'user' => $user,
                'activation' => $userAdmin->role,
                'url' => url()->current()
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
            $urls = [];
            if ($user->role == User::DISTRIBUTOR_ROLE) {
                $urls = $this->dbDistributorRepository->findByUserID($user->id);
            }
            if ($user->role == User::COMMERCE_ROLE) {
                $urls = $this->dbCommerceRepository->findByUserID($user->id);
            }
            return view('admin.users.add-documents', [
                'urls' => $urls,
                'user' => $user,
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
                mkdir($documents, 0777, true);
            }

            $rut = $_FILES['rut'];
            $commerceRoom = $_FILES['commerce_room'];
            $ccLegalRepresentative = $_FILES['cc_legal_representative'];
            $establishmentImage = $_FILES['establishment_image'];
            $interiorImage = $_FILES['interior_image'];
            $contract = $_FILES['contract'];

            if ($rut['name'] != '') {
                $ext = pathinfo($rut['name'], PATHINFO_EXTENSION);
                $urlRut = "{$documents}/1.Rut.{$ext}";
                $destinationRoute = $urlRut;
                move_uploaded_file($rut['tmp_name'], $destinationRoute);
            }

            if ($commerceRoom['name'] != '') {
                $ext = pathinfo($commerceRoom['name'], PATHINFO_EXTENSION);
                $urlCommerceRoom = "{$documents}/2.Camara de comercio.{$ext}";
                $destinationRoute = $urlCommerceRoom;
                move_uploaded_file($commerceRoom['tmp_name'], $destinationRoute);
            }

            if ($ccLegalRepresentative['name'] != '') {
                $ext = pathinfo($ccLegalRepresentative['name'], PATHINFO_EXTENSION);
                $urlCCLegalRepresentative = "{$documents}/3.Cedula del representante legal.{$ext}";
                $destinationRoute = $urlCCLegalRepresentative;
                move_uploaded_file($ccLegalRepresentative['tmp_name'], $destinationRoute);
            }

            if ($establishmentImage['name'] != '') {
                $ext = pathinfo($establishmentImage['name'], PATHINFO_EXTENSION);
                $urlEstablishmentImage = "{$documents}/4.Foto del establecimiento.{$ext}";
                $destinationRoute = $urlEstablishmentImage;
                move_uploaded_file($establishmentImage['tmp_name'], $destinationRoute);
            }

            if ($interiorImage['name'] != '') {
                $ext = pathinfo($interiorImage['name'], PATHINFO_EXTENSION);
                $urlInteriorImage = "{$documents}/5.Foto estanteria, caja, bodega.{$ext}";
                $destinationRoute = $urlInteriorImage;
                move_uploaded_file($interiorImage['tmp_name'], $destinationRoute);
            }

            if ($contract['name'] != '') {
                $ext = pathinfo($contract['name'], PATHINFO_EXTENSION);
                $urlContract = "{$documents}/6.Contrato.{$ext}";
                $destinationRoute = $urlContract;
                move_uploaded_file($contract['tmp_name'], $destinationRoute);
            }

            if ($request->role == User::COMMERCE_ROLE) {
                $this->dbCommerceRepository->saveDocuments(
                    $request->user_id,
                    isset($urlRut) ? $urlRut : null,
                    isset($urlCommerceRoom) ? $urlCommerceRoom : null,
                    isset($urlCCLegalRepresentative) ? $urlCCLegalRepresentative : null,
                    isset($urlEstablishmentImage) ? $urlEstablishmentImage : null,
                    isset($urlInteriorImage) ? $urlInteriorImage : null ,
                    isset($urlContract) ? $urlContract : null
                );
                return redirect('admin/commerce-list');
            }
            if ($request->role == User::DISTRIBUTOR_ROLE) {
                $this->dbDistributorRepository->saveDocuments(
                    $request->user_id,
                    isset($urlRut) ? $urlRut : null,
                    isset($urlCommerceRoom) ? $urlCommerceRoom : null,
                    isset($urlCCLegalRepresentative) ? $urlCCLegalRepresentative : null,
                    isset($urlEstablishmentImage) ? $urlEstablishmentImage : null,
                    isset($urlInteriorImage) ? $urlInteriorImage : null ,
                    isset($urlContract) ? $urlContract : null
                );
                return redirect('admin/distributor-list');
            }
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param UserLocation $userLocation
     * @param int $userLocationID
     * @return Application|RedirectResponse|Redirector
     */
    public function deleteLocation(UserLocation $userLocation, int $userLocationID)
    {
        $adminUser = Session::get('user');
        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $userLocation = $this->dbUserRepository->findByUserLocationID($userLocationID);
            $this->dbUserRepository->deleteUserLocation($userLocationID);
            $user = $this->dbUserRepository->findByID($userLocation->user_id);

            if ($user->role == User::DISTRIBUTOR_ROLE) {
                return redirect('admin/distributor-list');
            }
            if ($user->role == User::COMMERCE_ROLE) {
                return redirect('admin/commerce-list');
            }
            if ($user->role == User::USER_ROLE) {
                return redirect('admin/client-list');
            }

        } else {
            return redirect('admin/user-session');
        }
    }
}

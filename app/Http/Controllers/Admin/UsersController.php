<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUserLocation;
use App\Http\Requests\Admin\Users\IndexUserLocation;
use App\Http\Requests\Admin\Users\LoginUsers;
use App\Models\SessionLog;
use App\Models\User;
use App\Models\UserLocation;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use App\Repositories\Contracts\SendSMSServiceRepositoryInterface;
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
     * @var SendSMSServiceRepositoryInterface
     */
    private $sendSMSService;

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
     * @var DbAdminUsersRepositoryInterface
     */
    private $dbAdminUserRepository;

    /**
     * UsersController constructor.
     * @param SendSMSServiceRepositoryInterface $sendSMSService
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     * @param DbAdminUsersRepositoryInterface $dbAdminUserRepository
     */
    public function __construct(
        SendSMSServiceRepositoryInterface $sendSMSService,
        DbUsersRepositoryInterface $dbUserRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbClientRepositoryInterface $dbClientRepository,
        DbAdminUsersRepositoryInterface $dbAdminUserRepository
    ) {
        $this->sendSMSService = $sendSMSService;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbClientRepository = $dbClientRepository;
        $this->dbAdminUserRepository = $dbAdminUserRepository;
    }

    /**
     * @param Request $request
     * @return false[]
     */
    public function validateSMS(Request $request): array
    {
        if (! env('SMS_ENABLED')) {
            if ($request->ajax()) {
                return [
                    'validate' => false,
                    'redirect' => url('admin/login-user'),
                ];
            }
        }

        return [];
    }

    /**
     * @param Request $request
     * @return Factory|Application|RedirectResponse|Redirector|View
     */
    public function validateOTP(Request $request)
    {
        if (is_null($request['phone']) && is_null($request['code']) ) {
            return view('vendor.brackets.admin-auth.admin.auth.login', [
                'error' => 'Por favor ingresa el dato requerido.',
            ]);
        }

        if (! is_null($request['phone'])) {
            $user = $this->dbUserRepository->findUserByPhone($request['phone']);
            if (is_null($user)) {
                return view('vendor.brackets.admin-auth.admin.auth.login', [
                    'error' => 'No encontramos ningún usuario asociado a este nuḿero, por favor comunicate con un administrador.',
                ]);
            }

            $otp = $this->generateOTP($user);
            $message = sprintf('%s %d', 'Tu código de verificación de nuap es', $otp);
            $this->sendSMSService->sendMessage(
                $message,
                $user->phone,
                ! is_null($user->country_code) ? $user->country_code : 57
            );

            return view('vendor.brackets.admin-auth.admin.auth.login', [
                'success' => 'Te hemos enviado un mensaje de texto con el código de verificación.',
                'phone_validated' => $request['phone'],
                'send_message' => true
            ]);
        }

        if (! is_null($request['code'])) {
            $user = $this->dbUserRepository->findByOTPCodeAndPhone($request['code'], $request['phone_validated']);
            if (is_null($user)) {
                return view('vendor.brackets.admin-auth.admin.auth.login', [
                    'error' => 'El código es incorrecto por favor vuelve a intentarlo.',
                    'valid_phone' => true,
                    'phone_validated' => $request['phone'],
                    'send_message' => true
                ]);
            }

            $user->otp = null;
            $user->phone_validated = true;
            $user->phone_validated_date = now();
            $user->save();

            return view('vendor.brackets.admin-auth.admin.auth.login-user', []);
        }

        return view('vendor.brackets.admin-auth.admin.auth.login', []);
    }

    /**
     * @param LoginUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function loginUser(LoginUsers $request)
    {
        $password = md5($request['password']);

        $user = $this->dbUserRepository->findUserByEmailAndPassword($request['email'], $password);

        if (is_null($user)) {
            $user = $this->dbUserRepository->findUserByPhoneAndPassword($request['email'], $password);
            if (is_null($user)) {
                return view('vendor.brackets.admin-auth.admin.auth.login-user', [
                    'error' => 'Credenciales inválidas, intenta de nuevo.',
                ]);
            }
        }

        if (! $user->status) {
            return view('vendor.brackets.admin-auth.admin.auth.login-user', [
                'error' => 'Usuario inactivo, comunicate con un administrador.'
            ]);
        }

        if (env('SMS_ENABLED')) {
            if (! $user->phone_validated) {
                return view('vendor.brackets.admin-auth.admin.auth.login-user', [
                    'error' => 'Debes validar tu número de celular para poder ingresar',
                    'valid_phone' => true
                ]);
            } else {
                $user->phone_validated = false;
                $user->save();
            }
        }

        /* @var User $user */
        $this->dbUserRepository->updateLastLogin($user->id, now());

        $logSession = new SessionLog();
        $logSession->user_id = $user->id;
        $logSession->user_type = $user->role;
        $logSession->login_date = now();
        $logSession->save();

        if ($user->role == User::ADMIN_ROLE) {
            $admin = $this->dbAdminUserRepository->findByUserID($user->id);
            $user->name = $admin->name.' '.$admin->last_name;
            Session::put('user', $user);
            return redirect('admin/distributor-list');
        }

        if ($user->role == User::DISTRIBUTOR_ROLE) {
            $distributor = $this->dbDistributorRepository->findByUserID($user->id);
            $user->name = $distributor->business_name;
            Session::put('user', $user);
            return redirect('/admin/distributor');
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
            return redirect('/admin/distributor');
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
                'country_code' => $user->country_code,
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
                $data['country_code_legal_representative'] = $distributor->country_code_legal_representative;
                $data['contact_legal_representative'] = $distributor->contact_legal_representative;
                $data['shipping_cost'] = $distributor->shipping_cost;
                $data['distance'] = $distributor->distance;
                $data['country_code_user'] = $user->country_code;
                $data['country_code'] = $distributor->country_code;

                return view('admin.distributors.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->name,
                    'role' => $userAdmin->role,
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
                $data['country_code_legal_representative'] = $commerce->country_code_legal_representative;
                $data['contact_legal_representative'] = $commerce->contact_legal_representative;
                $data['shipping_cost'] = $commerce->shipping_cost;
                $data['distance'] = $commerce->distance;
                $data['country_code_user'] = $user->country_code;
                $data['country_code'] = $commerce->country_code;

                return view('admin.commerces.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->name,
                    'role' => $userAdmin->role,
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
                    'activation' => $userAdmin->name,
                    'role' => $userAdmin->role,
                    'url' => $client->resource_url,
                    'business_name' => $client->name
                ]);
            }

            if ($user->role == User::ADMIN_ROLE) {
                $admin = $this->dbAdminUserRepository->findByUserID($user->id);
                $data['client_id'] = $admin->id;
                $data['name'] = $admin->name;
                $data['last_name'] = $admin->last_name;
                $data['position'] = $admin->position;
                $data['identity_number'] = $admin->identity_number;

                return view('admin.admin-users.edit', [
                    'user' => json_encode($data),
                    'activation' => $userAdmin->name,
                    'role' => $userAdmin->role,
                    'url' => $admin->resource_url,
                    'business_name' => $admin->name
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
                    ['id', 'country', 'city', 'location', 'neighborhood', 'address'],
                    ['id', 'country', 'city', 'location', 'neighborhood', 'address']
                );

            return view('admin.users.add-location', [
                'data' => $data,
                'user' => $user,
                'activation' => $userAdmin->name,
                'role' => $userAdmin->role,
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
            if ($request->role == User::USER_ROLE){
                return redirect('admin/client-list');
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
                'activation' => $userAdmin->name,
                'role' => $user->role,
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
            $header = $_FILES['header'];
            $logo= $_FILES['logo'];

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

            if ($header['name'] != '') {
                $ext = pathinfo($header['name'], PATHINFO_EXTENSION);
                $urlHeader = "{$documents}/7.Header.{$ext}";
                $destinationRoute = $urlHeader;
                move_uploaded_file($header['tmp_name'], $destinationRoute);
            }

            if ($logo['name'] != '') {
                $ext = pathinfo($logo['name'], PATHINFO_EXTENSION);
                $urlLogo = "{$documents}/8.Logo.{$ext}";
                $destinationRoute = $urlLogo;
                move_uploaded_file($logo['tmp_name'], $destinationRoute);
            }

            if ($request->role == User::COMMERCE_ROLE) {
                $this->dbCommerceRepository->saveDocuments(
                    $request->user_id,
                    isset($urlRut) ? $urlRut : null,
                    isset($urlCommerceRoom) ? $urlCommerceRoom : null,
                    isset($urlCCLegalRepresentative) ? $urlCCLegalRepresentative : null,
                    isset($urlEstablishmentImage) ? $urlEstablishmentImage : null,
                    isset($urlInteriorImage) ? $urlInteriorImage : null ,
                    isset($urlContract) ? $urlContract : null,
                    isset($urlHeader) ? $urlHeader : null,
                    isset($urlLogo) ? $urlLogo : null
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
                    isset($urlContract) ? $urlContract : null,
                    isset($urlHeader) ? $urlHeader : null,
                    isset($urlLogo) ? $urlLogo : null
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

    /**
     * @param User $user
     * @return int
     */
    public function generateOTP(User $user): int
    {
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();

        return $otp;
    }
}

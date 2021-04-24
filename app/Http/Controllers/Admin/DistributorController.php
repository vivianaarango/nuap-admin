<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\CreateDistributor;
use App\Http\Requests\Admin\Distributor\IndexCommission;
use App\Http\Requests\Admin\Distributor\IndexDistributor;
use App\Mail\SendEmail;
use App\Models\Distributor;
use App\Models\User;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use App\Repositories\Contracts\DbPaymentRepositoryInterface;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class DistributorController
 * @package App\Http\Controllers\Admin
 */
class DistributorController extends Controller
{
    /**
     * @var string
     */
    private $dateToSearch;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbPaymentRepositoryInterface
     */
    private $dbPaymentRepository;

    /**
     * @var DbOrderRepositoryInterface
     */
    private $dbOrderRepository;

    /**
     * @var DbTicketRepositoryInterface
     */
    private $dbTicketRepository;

    /**
     * @var DbProductRepositoryInterface
     */
    private $dbProductRepository;

    /**
     * DistributorController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbPaymentRepositoryInterface $dbPaymentRepository
     * @param DbOrderRepositoryInterface $dbOrderRepository
     * @param DbTicketRepositoryInterface $dbTicketRepository
     * @param DbProductRepositoryInterface $dbProductRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbPaymentRepositoryInterface $dbPaymentRepository,
        DbOrderRepositoryInterface $dbOrderRepository,
        DbTicketRepositoryInterface $dbTicketRepository,
        DbProductRepositoryInterface $dbProductRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbPaymentRepository = $dbPaymentRepository;
        $this->dbOrderRepository = $dbOrderRepository;
        $this->dbTicketRepository = $dbTicketRepository;
        $this->dbProductRepository = $dbProductRepository;
    }

    /**
     * @param Request $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            return view('admin.distributors.init', [
                'activation' => $user->name,
                'role' => $user->role,
                'pending_payments' => count($this->dbPaymentRepository->findPendingByUserID($user->id)),
                'order_process' => count($this->dbOrderRepository->findInProgressByUserID($user->id)),
                'open_tickets' => count($this->dbTicketRepository->findOpenByUserID($user->id)),
                'approved_products' => count($this->dbProductRepository->findApprovedProducts($user->id)),
                'today_total' => '$ ' . $this->formatCurrency($this->dbOrderRepository->findTodayOrdersDeliveredByUserID($user->id)),
                'month_total' => '$ ' . $this->formatCurrency($this->dbOrderRepository->findMonthDeliveredByUserID($user->id)),
                'week_total' => '$ ' . $this->formatCurrency($this->dbOrderRepository->findLastWeekOrdersDeliveredByUserID($user->id))
            ]);
        }
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = 'COP'): string
    {
        $currencies['COP'] = array(0, ',', '.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }

    /**
     * @param IndexDistributor $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexDistributor $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $this->dateToSearch = date("Y-m-d");
            $days = $request['days'];
            if ($request->ajax()) {
                $url = url()->previous();
                $parts = parse_url($url);
                if (isset($parts['query'])){
                    parse_str($parts['query'], $query);
                    $days = $query['days'] ?? null;
                }
            }

            if (!is_null($days) && $days != 0) {
                $this->dateToSearch = date("Y-m-d",strtotime($this->dateToSearch." - ".$days." days"));
            } else {
                $this->dateToSearch = date("Y-m-d",strtotime($this->dateToSearch." + 1 days"));
            }

            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Distributor::class)
                ->modifyQuery(function($query) {
                    $query->select(
                            'users.status',
                            'users.last_logged_in',
                            'distributors.*'
                        )->where('users.role', User::DISTRIBUTOR_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('users', 'users.id', '=', 'distributors.user_id')
                        ->orderBy('user_id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['user_id', 'commission', 'name_legal_representative', 'business_name', 'nit'],
                    ['user_id', 'commission', 'name_legal_representative', 'business_name', 'nit']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.distributors.index', [
                'data' => $data,
                'activation' => $user->name,
                'role' => $user->role,
                'days' => $days
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.distributors.create', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateDistributor $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateDistributor $request)
    {
        $countryCode = $request['country_code'];
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['country_code'] = $countryCode;
            Distributor::create($data);

            Mail::to($user->email)->send(new SendEmail(
                    $data['business_name'],
                    'Ya eres parte de Nuap, gracias por unirtenos ',
                    '¡Bienvenido!.'
                )
            );
        }

        if ($request->ajax()) {
           return [
                'redirect' => url('admin/distributor-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/distributor-list');
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'phone' => ['required', 'string', 'unique:users,phone,'.$request['user_id']],
            'country_code' => ['required', 'string'],
            'country_code_user' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'business_name' => ['required', 'string'],
            'nit' => ['required', 'string'],
            'second_phone' => ['required', 'string'],
            'commission' => ['required', 'numeric', 'min:0.0','max:100.00'],
            'name_legal_representative' => ['required', 'string'],
            'cc_legal_representative' => ['required', 'string'],
            'contact_legal_representative' => ['required', 'string'],
            'country_code_legal_representative' => ['required', 'string'],
            'shipping_cost' => ['nullable', 'numeric'],
            'distance' => ['nullable', 'numeric']
        ]);

        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $user = $this->dbUserRepository->findByID($request['user_id']);
            if ($request['phone'] != $user->phone) {
                $phoneValidated = false;
            }
            $this->dbUserRepository->updateUser(
                $request['user_id'],
                $request['email'],
                $request['country_code_user'],
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );

            $this->dbDistributorRepository->updateDistributor(
                $request['distributor_id'],
                $request['user_id'],
                $request['business_name'],
                $request['nit'],
                $request['country_code'],
                $request['second_phone'],
                $request['commission'],
                $request['name_legal_representative'],
                $request['cc_legal_representative'],
                $request['country_code_legal_representative'],
                $request['contact_legal_representative'],
                $request['shipping_cost'],
                $request['distance']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/distributor-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/user-session');
    }

    /**
     * @param IndexCommission $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function editCommission(IndexCommission $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Distributor::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'distributors.*',
                        'distributors.commission',
                        'distributors.name_legal_representative'
                    )
                        ->join('users', 'users.id', '=', 'distributors.user_id')
                        ->where('users.role', User::DISTRIBUTOR_ROLE)
                        ->where('users.status', User::STATUS_ACTIVE)
                        ->orderBy('distributors.id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'business_name', 'commission', 'name_legal_representative'],
                    ['id', 'business_name', 'commission', 'name_legal_representative']
                );

            if ($request->ajax()) {
                if ($request->has('bulk')) {
                    return [
                        'bulkItems' => $data->pluck('id')
                    ];
                }
                return ['data' => $data];
            }

            return view('admin.distributors.commission', [
                'data' => $data,
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function updateCommission(Request $request)
    {
        $newCommission = $request->data['commission'];
        $idsDistributors = $request->data['ids'];

        foreach ($idsDistributors as $item) {
            $this->dbDistributorRepository->updateCommission($item, $newCommission);
        }

        if ($request->ajax()) {
            return response(['message' => trans('Se ha actualizado la comisión correctamente')]);
        }

        return redirect()->back();
    }
}

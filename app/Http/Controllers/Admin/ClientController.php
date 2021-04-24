<?php
namespace App\Http\Controllers\Admin;

use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Client\CreateClient;
use App\Http\Requests\Admin\Client\IndexClient;
use App\Mail\SendEmail;
use App\Models\Client;
use App\Models\User;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ClientController
 * @package App\Http\Controllers\Admin
 */
class ClientController extends Controller
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
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * ClientController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbClientRepository = $dbClientRepository;
    }

    /**
     * @param IndexClient $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexClient $request)
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
            $data = AdminListing::create(Client::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'users.status',
                        'users.last_logged_in',
                        'clients.*'
                    )->where('users.role', User::USER_ROLE)
                        ->where('last_logged_in', '<=', $this->dateToSearch)
                        ->join('users', 'users.id', '=', 'clients.user_id')
                        ->orderBy('user_id', 'desc');;
                })->processRequestAndGet(
                    $request,
                    ['id', 'user_id', 'last_name', 'name', 'identity_number'],
                    ['id', 'user_id', 'last_name', 'name', 'identity_number']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role, 'days' => $days];
            }

            return view('admin.clients.index', [
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
            return view('admin.clients.create', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateClient $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateClient $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            Client::create($data);

            Mail::to($user->email)->send(new SendEmail(
                    $data['name'],
                    'Ya eres parte de Nuap, gracias por unirtenos ',
                    '¡Bienvenido!.'
                )
            );
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/client-list'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/client-list');
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => ['nullable', 'string', 'email', 'unique:users,email,'.$request['user_id']],
            'country_code' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'unique:users,phone,'.$request['user_id']],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
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
                $request['country_code'],
                $request['phone'],
                isset($phoneValidated) ? $phoneValidated : true,
                is_null($request['password']) ? null : md5($request['password'])
            );
            $this->dbClientRepository->updateClient(
                $request['client_id'],
                $request['user_id'],
                $request['name'],
                $request['last_name'],
                $request['identity_number']
            );

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/client-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }
        }

        return redirect('admin/validate-session');
    }

    /**
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\CreateAdminUsers;
use App\Models\AdminUser;
use App\Models\Config;
use App\Models\User;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class AdminUsersController
 * @package App\Http\Controllers\Admin
 */
class AdminUsersController extends Controller
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
     * UsersController constructor.
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
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.admin-users.create', [
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param CreateAdminUsers $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(CreateAdminUsers $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $data = $request->getModifiedData();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            AdminUser::create($data);
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/admin-users-create'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/admin-users-create');
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function createConfig()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            $config = Config::first();

            $data = [];
            if (isset($config->id)) {
                $data['config_id'] = $config->id;
                $data['shipping_cost'] = $config->shipping_cost;
                $data['distance'] = $config->distance;
            }

            return view('admin.admin-users.config', [
                'config' => json_encode($data),
                'activation' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function storeConfig(Request $request)
    {
        $this->validate($request, [
            'config_id' => ['nullable', 'numeric'],
            'shipping_cost' => ['nullable', 'numeric'],
            'distance' => ['nullable', 'numeric']
        ]);

        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            if ($request['config_id']) {
                $config = Config::where('id', $request['config_id'])->first();
            } else {
                $data['shipping_cost'] = $request['shipping_cost'];
                $data['distance'] = $request['distance'];
                Config::create($data);
            }
        }

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/config-create'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/config-create');
    }
}

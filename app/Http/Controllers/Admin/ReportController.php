<?php
namespace App\Http\Controllers\Admin;

use App\Exports\DistributorSalesExport;
use App\Exports\PaymentsExport;
use App\Exports\PaymentsPendingExport;
use App\Exports\AllSalesExport;
use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ReportController
 * @package App\Http\Controllers\Admin
 */
class ReportController extends Controller
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
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * ReportController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbAdminUsersRepositoryInterface $dbAdminUserRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository,
        DbAdminUsersRepositoryInterface $dbAdminUserRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
        $this->dbAdminUserRepository = $dbAdminUserRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbClientRepository = $dbClientRepository;
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function users()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.reports.new-users', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function tickets()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.reports.tickets', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function loginUsers()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.reports.login-users', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    public function sales()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.reports.sales', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportPayments(): BinaryFileResponse
    {
        $date = now();
        return Excel::download(new PaymentsExport, 'pagos-aprobados-'.$date.'.xlsx');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function exportSales(Request $request): BinaryFileResponse
    {
        return Excel::download(new SalesExport(
            $request['month'],
            $request['user_type'],
            $this->dbCommerceRepository,
            $this->dbDistributorRepository,
            $this->dbClientRepository
        ), 'ventas-de-'.$request['month'].'-de-'.$request['user_type'].'.xlsx');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportPendingPayments(): BinaryFileResponse
    {
        $date = now();
        return Excel::download(new PaymentsPendingExport, 'pagos-pendientes-'.$date.'.xlsx');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function exportAllSales(Request $request): BinaryFileResponse
    {
        return Excel::download(new AllSalesExport(
            $request['init_date'],
            $request['finish_date'],
            $this->dbCommerceRepository,
            $this->dbDistributorRepository,
            $this->dbClientRepository
        ), 'ventas-de-'.$request['init_date'].'-a-'.$request['finish_date'].'.xlsx');
    }

    public function allSales()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.reports.all-sales', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }
}

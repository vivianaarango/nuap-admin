<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\IndexTicket;
use App\Mail\SendEmail;
use App\Models\AdminUser;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use App\Repositories\DbUsersRepository;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class TicketController
 * @package App\Http\Controllers\Admin
 */
class TicketController extends Controller
{
    /**
     * @var DbTicketRepositoryInterface
     */
    private $dbTicketRepository;

    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * TicketController constructor.
     * @param DbTicketRepositoryInterface $dbTicketRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbUsersRepository $dbUserRepository
     */
    public function __construct(
        DbTicketRepositoryInterface $dbTicketRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbUsersRepository $dbUserRepository
    ) {
        $this->dbTicketRepository = $dbTicketRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbUserRepository = $dbUserRepository;
    }

    /**
     * @param IndexTicket $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexTicket $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Ticket::class)
                ->modifyQuery(function($query) {
                    $query->where('user_id', Session::get('user')->id)
                        ->orderBy('status', 'asc')
                        ->orderBy('updated_at', 'desc')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'issues', 'status', 'description', 'init_date', 'updated_at'],
                    ['id', 'issues', 'status', 'description', 'init_date', 'updated_at']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.tickets.index', [
                'data' => $data,
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
    public function create()
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            return view('admin.tickets.create', [
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $user = Session::get('user');
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            $ticket['user_id'] = $user->id;
            $ticket['user_type'] = $user->role;
            $ticket['issues'] = $request['issues'];
            $ticket['init_date'] = now();
            $ticket['finish_date'] = null;
            $ticket['description'] = $request['message'];
            $ticket['status'] = Ticket::PENDING_ADMIN;
            $newTicket = Ticket::create($ticket);

            $message['ticket_id'] = $newTicket->id;
            $message['message'] = $request['message'];
            $message['sender_id'] = $user->id;
            $message['sender_type'] = $user->role;
            $message['sender_date'] = now();
            TicketMessage::create($message);

            $admin = AdminUser::all();
            foreach ($admin as $item) {
                $sender = $this->dbUserRepository->findByID($item->user_id);
                Mail::to($sender->email)->send(new SendEmail(
                        '',
                        '¡Han creado un nuevo ticket!.',
                        'Tienes un nuevo mensaje de soporte, responde lo antes posible'
                    )
                );
            }

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/ticket-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }

            return redirect('admin/ticket-list');

        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param IndexTicket $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function adminList(IndexTicket $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Ticket::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'users.email',
                        'tickets.*'
                    )->join('users', 'users.id', '=', 'tickets.user_id')
                        ->orderBy('status', 'asc')
                        ->orderBy('updated_at', 'desc')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'issues', 'status', 'description', 'init_date', 'updated_at'],
                    ['id', 'email', 'issues', 'status', 'description', 'init_date', 'updated_at']
                );

            foreach ($data as $item){
                if ($item->user_type === User::DISTRIBUTOR_ROLE){
                    $ticketUser =$this->dbDistributorRepository->findByUserID($item->user_id);
                    $item->email = $ticketUser->business_name;
                }

                if ($item->user_type === User::COMMERCE_ROLE){
                    $ticketUser =$this->dbCommerceRepository->findByUserID($item->user_id);
                    $item->email = $ticketUser->business_name;
                }
            }

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.tickets.admin-index', [
                'data' => $data,
                'activation' => $user->name,
                'role' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Ticket $ticket
     * @return Factory|Application|RedirectResponse|Redirector|View
     */
    public function view(Ticket $ticket)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && ($userAdmin->role == User::ADMIN_ROLE || $userAdmin->role == User::DISTRIBUTOR_ROLE)) {
            $data = $this->dbTicketRepository->findMessagesByTicket($ticket->id);

            foreach ($data as $item){
                $senderDate = $item->sender_date;
                $item->sender_date = $this->formatDate($senderDate);
                $item->role = $this->formatHour($senderDate);
            }

            return view('admin.tickets.view', [
                'tittle' => $ticket->issues,
                'ticket_id' => $ticket->id,
                'data' => $data,
                'activation' => $userAdmin->name,
                'role' => $userAdmin->role,
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Request $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function sendMessage(Request $request)
    {
        $user = Session::get('user');

        if (isset($user) && ($user->role == User::ADMIN_ROLE || $user->role == User::DISTRIBUTOR_ROLE)) {
            $ticket = $this->dbTicketRepository->findByID($request['ticket_id']);
            $ticket->setUpdatedAt(now());
            if ($user->role === User::DISTRIBUTOR_ROLE) {
                $ticket['status'] = Ticket::PENDING_ADMIN;
            } else {
                $ticket['status'] = Ticket::PENDING_USER;
            }
            $ticket->save();

            $message['ticket_id'] = $request['ticket_id'];
            $message['message'] = $request['message'];
            $message['sender_id'] = $user->id;
            $message['sender_type'] = $user->role;
            $message['sender_date'] = now();

            $adminResponse = true;
            $messages = $this->dbTicketRepository->findMessagesByTicket($request['ticket_id']);

            foreach ($messages as $item) {
                if ($item->sender_type === User::ADMIN_ROLE) {
                    $sender = $this->dbUserRepository->findByID($item->user_id);
                    Mail::to($sender->email)->send(new SendEmail(
                            '',
                            '¡Han respondido tu mensaje!.',
                            'Tienes un nuevo mensaje de soporte, responde lo antes posible'
                        )
                    );
                } else {
                    $adminResponse = false;
                }
            }

            if (! $adminResponse) {
                $admin = AdminUser::all();
                foreach ($admin as $item) {
                    $sender = $this->dbUserRepository->findByID($item->user_id);
                    Mail::to($sender->email)->send(new SendEmail(
                            '',
                            '¡Han creado un nuevo ticket!.',
                            'Tienes un nuevo mensaje de soporte, responde lo antes posible'
                        )
                    );
                }
            }

            TicketMessage::create($message);

            if ($user->role === User::DISTRIBUTOR_ROLE) {
                return redirect('admin/ticket-list');
            }

            if ($user->role === User::ADMIN_ROLE) {
                return redirect('admin/ticket-admin-list');
            }
        }

        return redirect('/admin/user-session');
    }

    /**
     * @param Ticket $ticket
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function close(Ticket $ticket)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && $adminUser->role == User::ADMIN_ROLE) {
            $ticket = $this->dbTicketRepository->findByID($ticket->id);
            $ticket->status = Ticket::CLOSED;
            $ticket->save();
        } else {
            return redirect('admin/user-session');
        }
    }

    /**
     * @param string $date
     * @return string
     */
    private function formatDate(string $date): string
    {
        $date = explode(' ',$date);
        $hora = explode(':',$date[1]);
        if (!isset($hora[2])) $hora[2] = '00';
        $hora = date('g:i a',mktime($hora[0],$hora[1],$hora[2],0,0,0));
        $date = explode('-', $date[0]);
        $dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
        $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        $date = $dias[date('w',mktime(0,0,0,$date[1],$date[2],$date[0]))].', '.intval($date[2]).' '.$meses[intval($date[1])];
        return $date;
    }

    /**
     * @param string $date
     * @return string
     */
    private function formatHour(string $date): string
    {
        $date = explode(' ',$date);
        $hora = explode(':',$date[1]);
        if (!isset($hora[2])) $hora[2] = '00';
        $hora = date('g:i a',mktime($hora[0],$hora[1],$hora[2],0,0,0)); return $hora;
    }
}

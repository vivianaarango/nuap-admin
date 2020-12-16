<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\IndexTicket;
use App\Models\Ticket;
use App\Models\User;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class TicketController
 * @package App\Http\Controllers\Admin
 */
class TicketController extends Controller
{
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
                        ->orderBy('is_closed', 'asc')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'issues', 'is_closed', 'description', 'init_date'],
                    ['id', 'issues', 'is_closed', 'description', 'init_date']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.tickets.index', [
                'data' => $data,
                'activation' => $user->role
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
                'activation' => $user->role
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
            $ticket['is_closed'] = Ticket::NOT_CLOSED;
            $newTicket = Ticket::create($ticket);

            $message['ticket_id'] = $newTicket->id;
            $message['message'] = $request['message'];
            $message['sender_id'] = $user->id;
            $message['sender_type'] = $user->role;
            $message['sender_date'] = now();

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
                        ->orderBy('is_closed', 'asc')
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'email', 'issues', 'is_closed', 'description', 'init_date'],
                    ['id', 'email', 'issues', 'is_closed', 'description', 'init_date']
                );

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.tickets.admin-index', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }
}

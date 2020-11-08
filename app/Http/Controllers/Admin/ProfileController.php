<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Admin
 */
class ProfileController extends Controller
{
    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * ProfileController constructor.
     * @param DbUsersRepositoryInterface $dbUserRepository
     */
    public function __construct(
        DbUsersRepositoryInterface $dbUserRepository
    ) {
        $this->dbUserRepository = $dbUserRepository;
    }

    /**
     * @return Factory|Application|View
     */
    public function edit()
    {
        $user = Session::get('user');
        if (!is_null($user)) {
            return view('admin.profile.edit-profile', [
                'user' => $user,
                'activation' => $user->role
            ]);
        } else {
            return redirect('admin/login');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string']
        ]);

        $user = $this->dbUserRepository->updateUser(
            $request['id'],
            $request['name'],
            $request['lastname'],
            $request['phone'],
            $request['email']
        );

        Session::put('user', $user);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/edit-profile')
            ];
        }

        return redirect('admin/edit-profle');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    /*public function editPassword(Request $request)
    {
        $this->setUser($request);

        return view('admin.profile.edit-password', [
            'adminUser' => $this->adminUser,
        ]);
    }*/


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response|array
     */
    /*public function updatePassword(Request $request)
    {
        $this->setUser($request);

        if ($request->ajax()) {
            return ['redirect' => url('admin/password'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/password');
    }*/
}

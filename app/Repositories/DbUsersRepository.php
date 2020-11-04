<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class DbUsersRepository
 * @package App\Repositories
 */
class DbUsersRepository implements DbUsersRepositoryInterface
{
    /**
     * Login to users type admin or wholesaler
     *
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findUserByEmailAndPassword(string $email, string $password): Collection
    {
        return User::where('email', $email)
            ->where('password', $password)
            ->where('status', User::STATUS_ACTIVE)
            ->whereIn('role', [User::ADMIN_ROLE, User::WHOLESALER_ROLE])
            ->get();
    }

    /**
     * @return User[]
     */
    public function getUsers(): iterable
    {
        return User::all();
    }
}

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
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findUserByEmailAndPassword(string $email, string $password): Collection
    {
        return User::where('email', $email)
            ->where('password', $password)
            ->where('status', User::STATUS_ACTIVE)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return User::all();
    }
}

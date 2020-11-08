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
     * @param int $userID
     * @param string $name
     * @param string $lastname
     * @param string $phone
     * @param string $email
     * @return User
     */
    public function updateUser(
        int $userID,
        string $name,
        string $lastname,
        string $phone,
        string $email
    ): User {
        $user = $this->findById($userID);
        $user->name = $name;
        $user->lastname = $lastname;
        $user->phone = $phone;
        $user->email = $email;
        $user->save();

        return $user;
    }

    /**
     * @param int $userID
     * @return User
     */
    public function findByID(int $userID): User
    {
        return User::where('status', User::STATUS_ACTIVE)
            ->findOrFail($userID);
    }

    /**
     * @param int $userID
     * @param string $password
     * @return User
     */
    public function updatePassword(
        int $userID,
        string $password
    ): User {
        $user = $this->findById($userID);
        $user->password = $password;
        $user->save();

        return $user;
    }
}

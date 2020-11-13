<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Support\Collection;
use Exception;

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
    public function updateProfileUser(
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
        return User::findOrFail($userID);
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

    /**
     * @param int $userID
     * @param string $name
     * @param string $lastname
     * @param string $identityType
     * @param string $identityNumber
     * @param string $phone
     * @param string $email
     * @param string $role
     * @param float|null $commission
     * @param float|null $discount
     * @param string|null $password
     * @return User
     */
    public function updateUser(
        int $userID,
        string $name,
        string $lastname,
        string $identityType,
        string $identityNumber,
        string $phone,
        string $email,
        string $role,
        float $commission = null,
        float $discount = null,
        string $password = null
    ): User {
        $user = $this->findById($userID);
        $user->name = $name;
        $user->lastname = $lastname;
        $user->identity_type = $identityType;
        $user->identity_number = $identityNumber;
        $user->phone = $phone;
        $user->email = $email;
        $user->role = $role;

        if (!is_null($commission)){
            $user->commission = $commission;
        }
        if (!is_null($discount)){
            $user->discount = $discount;
        }
        if (!is_null($password)){
            $user->password = $password;
        }

        $user->save();

        return $user;
    }

    /**
     * @param int $userID
     * @param string $lastLogin
     * @return User
     */
    public function updateLastLogin(int $userID, string $lastLogin): User
    {
        $user = $this->findById($userID);
        $user->last_logged_in = $lastLogin;
        $user->save();

        return $user;
    }

    /**
     * @param int $userID
     * @return bool
     * @throws Exception
     */
    public function deleteUser(int $userID): bool
    {
        $user = $this->findById($userID);
        return $user->delete();
    }

    /**
     * @param int $userID
     * @param bool $status
     * @return User
     */
    public function changeStatus(int $userID, bool $status): User {
        $user = $this->findById($userID);
        $user->status = $status;
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @param string $phone
     * @param string $phone_validated
     * @param string $password
     * @param string $status
     * @param string $role
     * @param string $last_logged_in
     * @param string|null $phone_validated_date
     * @return User
     */
    public function createUser(
        string $email,
        string $phone,
        string $phone_validated,
        string $password,
        string $status,
        string $role,
        string $last_logged_in,
        string $phone_validated_date = null
    ): User {
        $user = new User();
        $user->email = $email;
        $user->phone = $phone;
        $user->$phone_validated = $phone_validated;
        $user->$password = $password;
        $user->$status = $status;
        $user->role = $role;
        $user->last_logged_in = $last_logged_in;
        $user->phone_validated_date = $phone_validated_date;
        $user->save();

        return $user;
    }
}

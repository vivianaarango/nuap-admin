<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Interface DbUsersRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbUsersRepositoryInterface
{
    /**
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findUserByEmailAndPassword(string $email, string $password): Collection;

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
    ): User;

    /**
     * @param int $userID
     * @return User
     */
    public function findByID(int $userID): User;

    /**
     * @param int $userID
     * @param string $password
     * @return User
     */
    public function updatePassword(
        int $userID,
        string $password
    ): User;

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
    ): User;

    /**
     * @param int $userID
     * @param string $lastLogin
     * @return User
     */
    public function updateLastLogin(int $userID, string $lastLogin): User;

    /**
     * @param int $userID
     * @return bool
     */
    public function deleteUser(int $userID): bool;

    /**
     * @param int $userID
     * @param bool $status
     * @return User
     */
    public function changeStatus(int $userID, bool $status): User;

    /**
     * @param string $email
     * @param string $phone
     * @param bool $phone_validated
     * @param string $password
     * @param bool $status
     * @param string $role
     * @param string $last_logged_in
     * @param string|null $phone_validated_date
     * @return User
     */
    public function createUser(
        string $email,
        string $phone,
        bool $phone_validated,
        string $password,
        bool $status,
        string $role,
        string $last_logged_in,
        string $phone_validated_date = null
    ): User;
}

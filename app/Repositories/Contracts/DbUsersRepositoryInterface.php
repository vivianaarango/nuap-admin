<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\UserLocation;
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
     * @param string $phone
     * @param string $email
     * @param bool $phoneValidated
     * @return User
     */
    public function updateProfileUser(
        int $userID,
        string $phone,
        string $email,
        bool $phoneValidated
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
     * @param string $email
     * @param string $phone
     * @param bool $phoneValidated
     * @param string|null $password
     * @return User
     */
    public function updateUser(
        int $userID,
        string $email,
        string $phone,
        bool $phoneValidated,
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
     * @param int $userLocation
     * @return bool
     */
    public function deleteUserLocation(int $userLocation): bool;

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

    /**
     * @param int $userLocationID
     * @return UserLocation
     */
    public function findByUserLocationID(int $userLocationID): UserLocation;
}

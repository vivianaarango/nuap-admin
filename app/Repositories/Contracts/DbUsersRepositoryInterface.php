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
}

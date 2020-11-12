<?php
namespace App\Repositories\Contracts;

use App\Models\AdminUser;
use App\Models\User;

/**
 * Interface DbAdminUsersRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbAdminUsersRepositoryInterface
{
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
    public function saveAdminUser(
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
    ): AdminUser;
}

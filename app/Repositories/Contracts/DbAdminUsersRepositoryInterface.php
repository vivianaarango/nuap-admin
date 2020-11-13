<?php
namespace App\Repositories\Contracts;

use App\Models\AdminUser;

/**
 * Interface DbAdminUsersRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbAdminUsersRepositoryInterface
{
    /**
     * @param int $userID
     * @param string $name
     * @param string $lastName
     * @param string $identityNumber
     * @param string|null $imageUrl
     * @return AdminUser
     */
    public function createAdminUser(
        int $userID,
        string $name,
        string $lastName,
        string $identityNumber,
        string $imageUrl = null
    ): AdminUser;
}

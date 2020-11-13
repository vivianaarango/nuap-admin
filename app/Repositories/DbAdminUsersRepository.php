<?php
namespace App\Repositories;

use App\Models\AdminUser;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;

/**
 * Class DbAdminUsersRepository
 * @package App\Repositories
 */
class DbAdminUsersRepository implements DbAdminUsersRepositoryInterface
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
    ): AdminUser {
        $adminUser = new AdminUser();
        $adminUser->user_id = $userID;
        $adminUser->name = $name;
        $adminUser->last_name = $lastName;
        $adminUser->identity_number = $identityNumber;
        $adminUser->image_url = $imageUrl ?? null;
        $adminUser->save();

        return $adminUser;
    }
}

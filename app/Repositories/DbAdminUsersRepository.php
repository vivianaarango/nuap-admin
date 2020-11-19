<?php
namespace App\Repositories;

use App\Models\AdminUser;
use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use Illuminate\Support\Collection;

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

    /**
     * @param int $userID
     * @return Collection
     */
    public function findByUserID(int $userID): Collection
    {
        return AdminUser::where('user_id', $userID)
            ->get();
    }

    /**
     * @param int $userID
     * @param string $name
     * @param string $lastName
     * @return AdminUser
     */
    public function updateProfileAdminUser(
        int $userID,
        string $name,
        string $lastName
    ): AdminUser {
        $adminUser = $this->findByUserID($userID)[0];
        $adminUser->name = $name;
        $adminUser->last_name = $lastName;
        $adminUser->save();

        return $adminUser;
    }
}

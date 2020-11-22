<?php
namespace App\Repositories;

use App\Models\Distributor;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbDistributorRepository
 * @package App\Repositories
 */
class DbDistributorRepository implements DbDistributorRepositoryInterface
{
    /**
     * @param int $userID
     * @return Distributor|null|Collection
     */
    public function findByUserID(int $userID): Distributor
    {
        return Distributor::where('user_id', $userID)
            ->first();
    }
}

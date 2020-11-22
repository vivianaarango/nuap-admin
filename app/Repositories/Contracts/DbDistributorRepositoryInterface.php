<?php
namespace App\Repositories\Contracts;

use App\Models\Distributor;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DbDistributorRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbDistributorRepositoryInterface
{
    /**
     * @param int $userID
     * @return Distributor|null|Collection
     */
    public function findByUserID(int $userID): Distributor;
}

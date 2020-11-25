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

    /**
     * @param int $distributorID
     * @param int $userID
     * @param string $businessName
     * @param string $nit
     * @param string $secondPhone
     * @param float $commission
     * @param string $type
     * @param string $nameLegalRepresentative
     * @param string $ccLegalRepresentative
     * @param string $contactLegalRepresentative
     * @return Distributor
     */
    public function updateDistributor(
        int $distributorID,
        int $userID,
        string $businessName,
        string $nit,
        string $secondPhone,
        float $commission,
        string $type,
        string $nameLegalRepresentative,
        string $ccLegalRepresentative,
        string $contactLegalRepresentative
    ): Distributor;

    /**
     * @param int $distributorID
     * @return Distributor
     */
    public function findByID(int $distributorID): Distributor;
}

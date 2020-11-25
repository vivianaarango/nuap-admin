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

    /**
     * @param int $distributorID
     * @param int $userID
     * @param string $businessName
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
        float $commission,
        string $type,
        string $nameLegalRepresentative,
        string $ccLegalRepresentative,
        string $contactLegalRepresentative
    ): Distributor {
        $distributor = $this->findById($distributorID);
        $distributor->user_id = $userID;
        $distributor->business_name = $businessName;
        $distributor->commission = $commission;
        $distributor->type = $type;
        $distributor->name_legal_representative = $nameLegalRepresentative;
        $distributor->cc_legal_representative = $ccLegalRepresentative;
        $distributor->contact_legal_representative = $contactLegalRepresentative;
        $distributor->save();

        return $distributor;
    }

    /**
     * @param int $distributorID
     * @return Distributor
     */
    public function findByID(int $distributorID): Distributor
    {
        return Distributor::findOrFail($distributorID);
    }
}

<?php
namespace App\Repositories;

use App\Models\Distributor;
use App\Models\User;
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
     * @param string $nit
     * @param string $secondPhone
     * @param float $commission
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
        string $nameLegalRepresentative,
        string $ccLegalRepresentative,
        string $contactLegalRepresentative
    ): Distributor {
        $distributor = $this->findById($distributorID);
        $distributor->user_id = $userID;
        $distributor->business_name = $businessName;
        $distributor->nit = $nit;
        $distributor->second_phone = $secondPhone;
        $distributor->commission = $commission;
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

    /**
     * @param int $userID
     * @param string|null $rut
     * @param string|null $commerceRoom
     * @param string|null $ccLegalRepresentative
     * @param string|null $establishmentImage
     * @param string|null $interiorImage
     * @param string|null $contract
     * @return Distributor
     */
    public function saveDocuments(
        int $userID,
        string $rut = null,
        string $commerceRoom = null,
        string $ccLegalRepresentative = null,
        string $establishmentImage = null,
        string $interiorImage = null,
        string $contract = null
    ): Distributor {
        $distributor = $this->findByUserID($userID);

        if (!is_null($rut))
            $distributor->url_rut = $rut;

        if (!is_null($commerceRoom))
            $distributor->url_commerce_room = $commerceRoom;

        if (!is_null($rut))
            $distributor->url_cc_legal_representative = $ccLegalRepresentative;

        if (!is_null($establishmentImage))
            $distributor->url_establishment_image = $establishmentImage;

        if (!is_null($interiorImage))
            $distributor->url_interior_image = $interiorImage;

        if (!is_null($contract))
            $distributor->url_contract = $contract;

        $distributor->save();
        return $distributor;
    }

    /**
     * @return Collection
     */
    public function findValidDistributorsToAddProducts(): Collection
    {
        return Distributor::with([
            'users' => function ($query) {
                $query->where('status', User::STATUS_ACTIVE)
                    ->where('role', User::COMMERCE_ROLE);
            },
        ])
        ->orderBy('id', 'desc')
        ->get();
    }

    /**
     * @param int $distributorID
     * @param float $commission
     * @return Distributor
     */
    public function updateCommission(int $distributorID, float $commission): Distributor
    {
        $distributor = $this->findByID($distributorID);
        $distributor->commission = $commission;
        $distributor->save();

        return $distributor;
    }
}

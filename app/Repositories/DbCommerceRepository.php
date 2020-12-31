<?php
namespace App\Repositories;

use App\Models\Commerce;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbCommerceRepository
 * @package App\Repositories
 */
class DbCommerceRepository implements DbCommerceRepositoryInterface
{
    /**
     * @param int $userID
     * @return Commerce|null|Collection
     */
    public function findByUserID(int $userID): Commerce
    {
        return Commerce::where('user_id', $userID)
            ->first();
    }

    /**
     * @param int $commerceID
     * @param int $userID
     * @param string $businessName
     * @param string $nit
     * @param string $secondPhone
     * @param float $commission
     * @param string $type
     * @param string $nameLegalRepresentative
     * @param string $ccLegalRepresentative
     * @param string $contactLegalRepresentative
     * @param float|null $shippingCost
     * @param float|null $distance
     * @return Commerce
     */
    public function updateCommerce(
        int $commerceID,
        int $userID,
        string $businessName,
        string $nit,
        string $secondPhone,
        float $commission,
        string $type,
        string $nameLegalRepresentative,
        string $ccLegalRepresentative,
        string $contactLegalRepresentative,
        float $shippingCost = null,
        float $distance = null
    ): Commerce {
        $commerce = $this->findById($commerceID);
        $commerce->user_id = $userID;
        $commerce->business_name = $businessName;
        $commerce->nit = $nit;
        $commerce->second_phone = $secondPhone;
        $commerce->commission = $commission;
        $commerce->type = $type;
        $commerce->name_legal_representative = $nameLegalRepresentative;
        $commerce->cc_legal_representative = $ccLegalRepresentative;
        $commerce->contact_legal_representative = $contactLegalRepresentative;
        $commerce->shipping_cost = $shippingCost;
        $commerce->distance = $distance;
        $commerce->save();

        return $commerce;
    }

    /**
     * @param int $commerceID
     * @return Commerce
     */
    public function findByID(int $commerceID): Commerce
    {
        return Commerce::findOrFail($commerceID);
    }

    /**
     * @param int $userID
     * @param string|null $rut
     * @param string|null $commerceRoom
     * @param string|null $ccLegalRepresentative
     * @param string|null $establishmentImage
     * @param string|null $interiorImage
     * @param string|null $contract
     * @return Commerce
     */
    public function saveDocuments(
        int $userID,
        string $rut = null,
        string $commerceRoom = null,
        string $ccLegalRepresentative = null,
        string $establishmentImage = null,
        string $interiorImage = null,
        string $contract = null
    ): Commerce {
        $commerce = $this->findByUserID($userID);

        if (!is_null($rut))
            $commerce->url_rut = $rut;

        if (!is_null($commerceRoom))
            $commerce->url_commerce_room = $commerceRoom;

        if (!is_null($rut))
            $commerce->url_cc_legal_representative = $ccLegalRepresentative;

        if (!is_null($establishmentImage))
            $commerce->url_establishment_image = $establishmentImage;

        if (!is_null($interiorImage))
            $commerce->url_interior_image = $interiorImage;

        if (!is_null($contract))
            $commerce->url_contract = $contract;

        $commerce->save();
        return $commerce;
    }

    /**
     * @return Collection
     */
    public function findValidCommercesToAddProducts(): Collection
    {
        return Commerce::with([
                'users' => function ($query) {
                    $query->where('status', User::STATUS_ACTIVE)
                        ->where('role', User::COMMERCE_ROLE);
                },
            ])
        ->orderBy('id', 'desc')
        ->get();
    }

    /**
     * @param int $commerceID
     * @param float $commission
     * @return Commerce
     */
    public function updateCommission(int $commerceID, float $commission): Commerce
    {
        $distributor = $this->findByID($commerceID);
        $distributor->commission = $commission;
        $distributor->save();

        return $distributor;
    }
}

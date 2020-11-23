<?php
namespace App\Repositories;

use App\Models\Commerce;
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
     * @param string $city
     * @param string $location
     * @param string $neighborhood
     * @param string $address
     * @param string $latitude
     * @param string $longitude
     * @param float $commission
     * @param string $type
     * @param string $nameLegalRepresentative
     * @param string $ccLegalRepresentative
     * @param string $contactLegalRepresentative
     * @return Commerce
     */
    public function updateCommerce(
        int $commerceID,
        int $userID,
        string $businessName,
        string $city,
        string $location,
        string $neighborhood,
        string $address,
        string $latitude,
        string $longitude,
        float $commission,
        string $type,
        string $nameLegalRepresentative,
        string $ccLegalRepresentative,
        string $contactLegalRepresentative
    ): Commerce {
        $commerce = $this->findById($commerceID);
        $commerce->user_id = $userID;
        $commerce->business_name = $businessName;
        $commerce->city = $city;
        $commerce->location = $location;
        $commerce->neighborhood = $neighborhood;
        $commerce->address = $address;
        $commerce->latitude = $latitude;
        $commerce->longitude = $longitude;
        $commerce->commission = $commission;
        $commerce->type = $type;
        $commerce->name_legal_representative = $nameLegalRepresentative;
        $commerce->cc_legal_representative = $ccLegalRepresentative;
        $commerce->contact_legal_representative = $contactLegalRepresentative;
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
}

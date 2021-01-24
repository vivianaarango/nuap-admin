<?php
namespace App\Repositories\Contracts;

use App\Models\Commerce;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DbCommerceRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbCommerceRepositoryInterface
{
    /**
     * @param int $userID
     * @return Commerce|null|Collection
     */
    public function findByUserID(int $userID): Commerce;

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
    ): Commerce;

    /**
     * @param int $commerceID
     * @return Commerce
     */
    public function findByID(int $commerceID): Commerce;

    /**
     * @param int $userID
     * @param string|null $rut
     * @param string|null $commerceRoom
     * @param string|null $ccLegalRepresentative
     * @param string|null $establishmentImage
     * @param string|null $interiorImage
     * @param string|null $contract
     * @param string|null $header
     * @param string|null $logo
     * @return Commerce
     */
    public function saveDocuments(
        int $userID,
        string $rut = null,
        string $commerceRoom = null,
        string $ccLegalRepresentative = null,
        string $establishmentImage = null,
        string $interiorImage = null,
        string $contract = null,
        string $header = null,
        string $logo = null
    ): Commerce;

    /**
     * @return Collection
     */
    public function findValidCommercesToAddProducts(): Collection;

    /**
     * @param int $commerceID
     * @param float $commission
     * @return Commerce
     */
    public function updateCommission(int $commerceID, float $commission): Commerce;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findUserAndCommerceByUserID(int $userID): Collection;
}

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
        string $contactLegalRepresentative
    ): Commerce;

    /**
     * @param int $commerceID
     * @return Commerce
     */
    public function findByID(int $commerceID): Commerce;
}

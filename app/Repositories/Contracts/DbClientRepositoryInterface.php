<?php
namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DbClientRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbClientRepositoryInterface
{
    /**
     * @param int $userID
     * @return Client|null|Collection
     */
    public function findByUserID(int $userID): Client;

    /**
     * @param int $clientID
     * @param int $userID
     * @param string $name
     * @param string $lastName
     * @param string $identityNumber
     * @return Client
     */
    public function updateClient(
        int $clientID,
        int $userID,
        string $name,
        string $lastName,
        string $identityNumber
    ): Client;

    /**
     * @param int $clientID
     * @return Client
     */
    public function findByID(int $clientID): Client;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findUserAndClientByUserID(int $userID): Collection;
}

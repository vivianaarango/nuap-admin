<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\Commerce;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DbClientRepository
 * @package App\Repositories
 */
class DbClientRepository implements DbClientRepositoryInterface
{
    /**
     * @param int $userID
     * @return Client
     */
    public function findByUserID(int $userID): Client
    {
        return Client::where('user_id', $userID)
            ->first();
    }

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
    ): Client {
        $client = $this->findById($clientID);
        $client->user_id = $userID;
        $client->name = $name;
        $client->last_name = $lastName;
        $client->identity_number = $identityNumber;
        $client->save();

        return $client;
    }

    /**
     * @param int $clientID
     * @return Client
     */
    public function findByID(int $clientID): Client
    {
        return Client::findOrFail($clientID);
    }
}

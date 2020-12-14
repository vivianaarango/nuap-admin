<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\UserLocation;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Support\Collection;
use Exception;

/**
 * Class DbUsersRepository
 * @package App\Repositories
 */
class DbUsersRepository implements DbUsersRepositoryInterface
{
    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * DbUsersRepository constructor.
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    public function __construct(
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbClientRepository = $dbClientRepository;
    }

    /**
     * Login to users type admin or wholesaler
     *
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findUserByEmailAndPassword(string $email, string $password): Collection
    {
        return User::where('email', $email)
            ->where('password', $password)
            ->where('status', User::STATUS_ACTIVE)
            ->whereIn('role', [User::ADMIN_ROLE, User::DISTRIBUTOR_ROLE])
            ->get();
    }

    /**
     * @param int $userID
     * @param string $phone
     * @param string $email
     * @param bool $phoneValidated
     * @return User
     */
    public function updateProfileUser(
        int $userID,
        string $phone,
        string $email,
        bool $phoneValidated
    ): User {
        $user = $this->findById($userID);
        $user->phone = $phone;
        $user->email = $email;

        if (!$phoneValidated) {
            $user->phone_validated = $phoneValidated;
            $user->phone_validated_date = null;
        }
        $user->save();
        return $user;
    }

    /**
     * @param int $userID
     * @return User
     */
    public function findByID(int $userID): User
    {
        return User::findOrFail($userID);
    }

    /**
     * @param int $userID
     * @param string $password
     * @return User
     */
    public function updatePassword(
        int $userID,
        string $password
    ): User {
        $user = $this->findById($userID);
        $user->password = $password;
        $user->save();

        return $user;
    }

    /**
     * @param int $userID
     * @param string $email
     * @param string $phone
     * @param bool $phoneValidated
     * @param string|null $password
     * @return User
     */
    public function updateUser(
        int $userID,
        string $email,
        string $phone,
        bool $phoneValidated,
        string $password = null
    ): User {
        $user = $this->findById($userID);
        $user->phone = $phone;
        $user->email = $email;
        if (!is_null($password)){
            $user->password = $password;
        }
        if (!$phoneValidated) {
            $user->phone_validated = $phoneValidated;
            $user->phone_validated_date = null;
        }

        $user->save();
        return $user;
    }

    /**
     * @param int $userID
     * @param string $lastLogin
     * @return User
     */
    public function updateLastLogin(int $userID, string $lastLogin): User
    {
        $user = $this->findById($userID);
        $user->last_logged_in = $lastLogin;
        $user->save();

        return $user;
    }

    /**
     * @param int $userID
     * @return bool
     * @throws Exception
     */
    public function deleteUser(int $userID): bool
    {
        $user = $this->findById($userID);

        if ($user->role == User::DISTRIBUTOR_ROLE){
            $distributor = $this->dbDistributorRepository->findByUserID($user->id);
            $distributor->delete();
        }

        if ($user->role == User::COMMERCE_ROLE){
            $commerce = $this->dbCommerceRepository->findByUserID($user->id);
            $commerce->delete();
        }

        if ($user->role == User::USER_ROLE){
            $client = $this->dbClientRepository->findByUserID($user->id);
            $client->delete();
        }

        return $user->delete();
    }

    /**
     * @param int $userLocation
     * @return bool
     * @throws Exception
     */
    public function deleteUserLocation(int $userLocation): bool {
        $userLoc = $this->findByUserLocationID($userLocation);
        return $userLoc->delete();
    }

    /**
     * @param int $userID
     * @param bool $status
     * @return User
     */
    public function changeStatus(int $userID, bool $status): User {
        $user = $this->findById($userID);
        $user->status = $status;
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @param string $phone
     * @param bool $phone_validated
     * @param string $password
     * @param bool $status
     * @param string $role
     * @param string $last_logged_in
     * @param string|null $phone_validated_date
     * @return User
     */
    public function createUser(
        string $email,
        string $phone,
        bool $phone_validated,
        string $password,
        bool $status,
        string $role,
        string $last_logged_in,
        string $phone_validated_date = null
    ): User {
        $user = new User();
        $user->email = $email;
        $user->phone = $phone;
        $user->$phone_validated = $phone_validated;
        $user->$password = $password;
        $user->$status = $status;
        $user->role = $role;
        $user->last_logged_in = $last_logged_in;
        $user->phone_validated_date = $phone_validated_date;
        $user->save();

        return $user;
    }

    /**
     * @param int $userLocationID
     * @return UserLocation
     */
    public function findByUserLocationID(int $userLocationID): UserLocation
    {
        return UserLocation::findOrFail($userLocationID);
    }
}

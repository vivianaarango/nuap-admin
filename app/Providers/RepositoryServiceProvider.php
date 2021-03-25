<?php
namespace App\Providers;

use App\Repositories\Contracts\DbAdminUsersRepositoryInterface;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use App\Repositories\Contracts\DbPaymentRepositoryInterface;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use App\Repositories\DbAdminUsersRepository;
use App\Repositories\DbBalanceRepository;
use App\Repositories\DbBankAccountRepository;
use App\Repositories\DbClientRepository;
use App\Repositories\DbCommerceRepository;
use App\Repositories\DbDistributorRepository;
use App\Repositories\DbOrderRepository;
use App\Repositories\DbPaymentRepository;
use App\Repositories\DbProductRepository;
use App\Repositories\DbTicketRepository;
use App\Repositories\DbUsersRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $classes = [
        DbUsersRepositoryInterface::class => DbUsersRepository::class,
        DbAdminUsersRepositoryInterface::class => DbAdminUsersRepository::class,
        DbDistributorRepositoryInterface::class => DbDistributorRepository::class,
        DbCommerceRepositoryInterface::class => DbCommerceRepository::class,
        DbClientRepositoryInterface::class => DbClientRepository::class,
        DbProductRepositoryInterface::class => DbProductRepository::class,
        DbTicketRepositoryInterface::class => DbTicketRepository::class,
        DbPaymentRepositoryInterface::class => DbPaymentRepository::class,
        DbBankAccountRepositoryInterface::class => DbBankAccountRepository::class,
        DbBalanceRepositoryInterface::class => DbBalanceRepository::class,
        DbOrderRepositoryInterface::class => DbOrderRepository::class
    ];

    /**
     * Register the repositories
     */
    public function register()
    {
        foreach ($this->classes as $interface => $implementation) {
            $this->app->singleton($interface, $implementation);
        }
    }

    /**
     * @return array
     */
    public function provides()
    {
        return array_keys($this->classes);
    }
}

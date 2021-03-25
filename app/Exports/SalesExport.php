<?php
namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class SalesExport
 * @package App\Exports
 */
class SalesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @var int
     */
    protected $month;

    /**
     * @var string
     */
    protected $userType;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * SalesExport constructor.
     * @param int $month
     * @param string $userType
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     */
    function __construct(
        int $month,
        string $userType,
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbClientRepositoryInterface $dbClientRepository
    ) {
        $this->month = $month;
        $this->userType = $userType;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbClientRepository = $dbClientRepository;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $year = (string) date("Y");
        $orders = Order::select(
            'users.email',
            'users.phone',
            'orders.*'
        )->join('users', 'users.id', '=', 'orders.user_id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('users.role', $this->userType)
            ->whereBetween('orders.created_at', [$year. "-".$this->month.'-01', $year. "-".$this->month.'-31'])
            ->orderBy('orders.id', 'desc')
            ->get();

        foreach ($orders as $item){
            if ($item->user_type === User::DISTRIBUTOR_ROLE){
                $data = $this->dbDistributorRepository->findUserAndDistributorByUserID($item->user_id);
                $item->seller_name = $data->first()->business_name;
            } else {
                $data = $this->dbCommerceRepository->findUserAndCommerceByUserID($item->user_id);
                $item->seller_name = $data->first()->business_name;
            }

            if ($item->client_type === User::USER_ROLE){
                $dataClient = $this->dbClientRepository->findByUserID($item->client_id);
                $item->client_name = $dataClient->first()->name;
            } else {
                $dataClient = $this->dbCommerceRepository->findUserAndCommerceByUserID($item->client_id);
                $item->client_name = $dataClient->first()->business_name;
            }
        }

        return $orders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Venta',
            'ID Vendedor',
            'Nombre Vendedor',
            'Tipo Vendedor',
            'Estado',
            'ID Cliente',
            'Nombre Cliente',
            'Tipo Cliente',
            'Total Productos',
            'Valor Productos',
            'Valor Envio',
            'Valor Descuento',
            'Total',
            'Fecha Creación'
        ];
    }

    /**
     * @param Product $export
     * @return array
     */
    public function map($export): array
    {
        /* @var Order $export*/
        return [
            (string) $export->id,
            (string) $export->user_id,
            (string) $export->seller_name,
            (string) $export->user_type,
            (string) $export->status,
            (string) $export->client_id,
            (string) $export->client_name,
            (string) $export->client_type,
            (string) $export->total_products,
            (string) $export->total_amount,
            (string) $export->delivery_amount,
            (string) $export->total_discount,
            (string) $export->total,
            (string) $export->created_at
        ];
    }
}

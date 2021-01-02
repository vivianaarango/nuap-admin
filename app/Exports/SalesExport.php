<?php
namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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
     * SalesExport constructor.
     * @param int $month
     * @param string $userType
     */
    function __construct(int $month, string $userType) {
        $this->month = $month;
        $this->userType = $userType;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $year = (string) date("Y");
        return Order::select(
            'users.email',
            'users.phone',
            'orders.*'
        )->join('users', 'users.id', '=', 'orders.user_id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('users.role', $this->userType)
            ->whereBetween('orders.created_at', [$year. "-".$this->month.'-01', $year. "-".$this->month.'-31'])
            ->orderBy('orders.id', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Venta',
            'ID Vendedor',
            'Tipo Vendedor',
            'Estado',
            'ID Cliente',
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
            (string) $export->user_type,
            (string) $export->status,
            (string) $export->client_id,
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

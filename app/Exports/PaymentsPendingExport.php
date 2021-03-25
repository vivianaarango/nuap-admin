<?php
namespace App\Exports;

use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class PaymentsPendingExport
 * @package App\Exports
 */
class PaymentsPendingExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Payment::select(
            'account_origin.id as origin_id',
            'account_origin.bank as origin_bank',
            'account_origin.account_type as origin_account_type',
            'account_origin.account as origin_account',
            'account_destination.id as destination_id',
            'account_destination.bank as destination_bank',
            'account_destination.account_type as destination_account_type',
            'account_destination.account as destination_account',
            'payments.*'
        )->join('users', 'users.id', '=', 'payments.user_id')
            ->join('bank_accounts AS account_origin', 'account_origin.id', '=', 'payments.account_admin_id')
            ->join('bank_accounts AS account_destination', 'account_destination.id', '=', 'payments.account_id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('payments.status', Payment::STATUS_PENDING)
            ->orderBy('request_date', 'asc')
            ->orderBy('payments.id', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tipo de Registro',
            'Versión',
            'Formato',
            'Código Regional',
            'Código de Instalación',
            'Código Empresa',
            'Entidad Origen',
            'Oficina Origen',
            'Cuenta Origen',
            'Tipo de Cuenta',
            'Número de Registros',
            'Valor Total',
            'Descripción',
            'Comprobante',
            'Tipo de Registro',
            'Entidad Destino',
            'Oficina Destino',
            'Cuenta Destino',
            'Tipo de Cuenta',
            'Código',
            'Factura',
            'Valor del Registro'
        ];
    }

    /**
     * @param Product $export
     * @return array
     */
    public function map($export): array
    {
        /* @var Payment $export*/
        return [
            (string) '',
            (string) '',
            (string) '',
            (string) '',
            (string) '',
            (string) '',
            (string) $export->origin_bank,
            (string) '',
            (string) $export->origin_account,
            (string) $export->origin_account_type,
            (string) '',
            (string) $export->value,
            (string) '',
            (string) '',
            (string) '',
            (string) $export->destination_bank,
            (string) '',
            (string) $export->destination_account,
            (string) $export->destination_account_type,
            (string) '',
            (string) '',
            (string) ''
        ];
    }
}

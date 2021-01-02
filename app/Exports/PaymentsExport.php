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
 * Class PaymentsExport
 * @package App\Exports
 */
class PaymentsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Payment::select(
            'bank_accounts.*',
            'users.email',
            'payments.*'
        )->join('users', 'users.id', '=', 'payments.user_id')
        ->join('bank_accounts', 'bank_accounts.id', '=', 'payments.account_id')
        ->where('users.status', User::STATUS_ACTIVE)
        ->where('payments.status', Payment::STATUS_APPROVED)
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
            'ID Pago',
            'ID Usuario',
            'Tipo Usuario',
            'Nombre Titular',
            'Documento Titular',
            'Tipo Documento',
            'Cuenta',
            'Tipo Cuenta',
            'Banco',
            'Certificado',
            'Valor',
            'Fecha Petición',
            'Fecha Pago',
            'Comprobante'
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
            (string) $export->id,
            (string) $export->user_id,
            (string) $export->user_type,
            (string) $export->owner_name,
            (string) $export->owner_document,
            (string) $export->owner_document_type,
            (string) $export->account,
            (string) $export->account_type,
            (string) $export->bank,
            (string) $export->certificate,
            (string) $export->value,
            (string) $export->request_date,
            (string) $export->payment_date,
            (string) $export->voucher
        ];
    }
}

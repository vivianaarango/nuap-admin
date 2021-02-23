<?php
namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class ClientsExport
 * @package App\Exports
 */
class ClientsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Client::select(
            'users.email',
            'users.phone',
            'clients.*'
        )->join('users', 'users.id', '=', 'clients.user_id')
            ->orderBy('clients.id', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Número de Identidad',
            'Correo Electrónico',
            'Celular'
        ];
    }

    /**
     * @param Product $export
     * @return array
     */
    public function map($export): array
    {
        /* @var Client $export*/
        return [
            (string) $export->id,
            (string) $export->name,
            (string) $export->last_name,
            (string) $export->identity_number,
            (string) $export->email,
            (string) $export->phone
        ];
    }
}

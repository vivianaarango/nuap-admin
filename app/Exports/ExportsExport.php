<?php
namespace App\Exports;

use App\Models\Export;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class ExportsExport
 * @package App\Exports
 */
class ExportsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Export::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            trans('admin.export.columns.id'),
            trans('admin.export.columns.title'),
            trans('admin.export.columns.perex'),
            trans('admin.export.columns.published_at'),
            trans('admin.export.columns.enabled'),
        ];
    }

    /**
     * @param Export $export
     * @return array
     */
    public function map($export): array
    {
        return [
            $export->id,
            $export->title,
            $export->perex,
            $export->published_at,
            $export->enabled,
        ];
    }
}

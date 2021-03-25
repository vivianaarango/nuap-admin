<?php
namespace App\Exports;

use App\Models\Product;
use Brackets\AdminGenerator\Generate\Export;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class ProductsExport
 * @package App\Exports
 */
class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Product::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Producto',
            'ID Usuario',
            'ID Categoría',
            'Producto',
            'Marca',
            'Descripción',
            'Estado',
            'Destacado',
            'Inventario',
            'Peso',
            'Largo',
            'Ancho',
            'Alto',
            'Precio de Compra',
            'Precio de Venta',
            'Descuento',
            'Tiene Descuento',
        ];
    }

    /**
     * @param Product $export
     * @return array
     */
    public function map($export): array
    {
        return [
            (string) $export->id,
            (string) $export->user_id,
            (string) $export->category_id,
            (string) $export->name,
            (string) $export->brand,
            (string) $export->description,
            (string) $export->status,
            (string) $export->is_featured,
            (string) $export->stock,
            (string) $export->weight,
            (string) $export->length,
            (string) $export->width,
            (string) $export->height,
            (string) $export->purchase_price,
            (string) $export->sale_price,
            (string) $export->special_price,
            (string) $export->has_special_price,
        ];
    }
}

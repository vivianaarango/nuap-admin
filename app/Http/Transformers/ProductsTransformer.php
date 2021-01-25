<?php
namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

/**
 * Class ProductsTransformer
 * @package App\Http\Transformers
 */
class ProductsTransformer extends TransformerAbstract
{
    /**
     * @param Product $product
     * @return array
     */
    public function transform(Product $product): array
    {
        return [
            'id' => $product->id,
            'user_id' => $product->name,
            'category_id' => $product->category_id,
            'name' => $product->name,
            'sku' => $product->sku,
            'brand' => $product->brand,
            'description' => $product->description,
            'status' => $product->status,
            'is_featured' => $product->is_featured,
            'stock' => $product->stock,
            'weight' => $product->weight,
            'length' => $product->length,
            'width' => $product->width,
            'height' => $product->height,
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
            'special_price' => $product->special_price,
            'has_special_price' => $product->has_special_price,
            'image' => $product->image,
            'position' => $product->position
        ];
    }
}
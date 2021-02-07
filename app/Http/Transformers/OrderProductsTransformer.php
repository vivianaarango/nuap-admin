<?php
namespace App\Http\Transformers;

use App\Models\OrderProduct;
use League\Fractal\TransformerAbstract;

/**
 * Class OrderProductsTransformer
 * @package App\Http\Transformers
 */
class OrderProductsTransformer extends TransformerAbstract
{
    /**
     * @param OrderProduct $product
     * @return array
     */
    public function transform(OrderProduct $product): array
    {
        return [
            'id' => $product->product_id,
            'user_id' => $product->name,
            'quantity' => $product->quantity,
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
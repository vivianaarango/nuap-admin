<?php
namespace App\Http\Transformers;

use App\Models\OrderProduct;
use App\Models\Product;
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
            'format_purchase_price' => '$ '.$this->formatCurrency($product->purchase_price),
            'format_sale_price' => '$ '.$this->formatCurrency($product->sale_price),
            'image' => $product->image,
            'position' => $product->position,
            'total_price' => '$ '.$this->calculateTotal($product)
        ];
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = 'COP'): string
    {
        $currencies['COP'] = array(0, ',', '.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }

    /**
     * @param OrderProduct $product
     * @return string
     */
    private function calculateTotal(OrderProduct $product): string
    {
        if ($product->has_special_price) {
            $discount = ($product->special_price * $product->sale_price) / 100;
            return $this->formatCurrency($product->sale_price - $discount);
        }

        return $this->formatCurrency($product->sale_price);
    }
}

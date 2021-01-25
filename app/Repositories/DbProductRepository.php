<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Class DbUsersRepository
 * @package App\Repositories
 */
class DbProductRepository implements DbProductRepositoryInterface
{
    /**
     * @param int $productID
     * @return Product
     */
    public function findByID(int $productID): Product
    {
        return Product::findOrFail($productID);
    }

    /**
     * @param int $productID
     * @param bool $status
     * @return Product
     */
    public function changeStatus(int $productID, bool $status): Product {
        $product = $this->findById($productID);
        $product->status = $status;
        $product->save();

        return $product;
    }

    /**
     * @param int $productID
     * @return bool
     * @throws Exception
     */
    public function delete(int $productID): bool
    {
        $product = $this->findById($productID);
        return $product->delete();
    }

    /**
     * @param int $productID
     * @param int $categoryID
     * @param string $name
     * @param string $sku
     * @param string $brand
     * @param string $description
     * @param int $stock
     * @param float $weight
     * @param float $length
     * @param float $width
     * @param float $height
     * @param float $purchasePrice
     * @param float $salePrice
     * @param float $specialPrice
     * @param string|null $image
     * @return Product
     */
    public function update(
        int $productID,
        int $categoryID,
        string $name,
        string $sku,
        string $brand,
        string $description,
        int $stock,
        float $weight,
        float $length,
        float $width,
        float $height,
        float $purchasePrice,
        float $salePrice,
        float $specialPrice,
        string $image = null
    ): Product {
        $product = $this->findByID($productID);
        $product->category_id = $categoryID;
        $product->name = $name;
        $product->sku = $sku;
        $product->brand = $brand;
        $product->description = $description;
        $product->stock = $stock;
        $product->weight = $weight;
        $product->length = $length;
        $product->width = $width;
        $product->height = $height;
        $product->purchase_price = $purchasePrice;
        $product->sale_price = $salePrice;
        $product->special_price = $specialPrice;

        if (!is_null($image))
            $product->image = $image;

        $product->save();

        return $product;
    }

    /**
     * @param int $categoryID
     * @param int $userID
     * @return Collection
     */
    public function findByCategoryAndUserID(int $categoryID, int $userID): Collection
    {
        return Product::where('user_id', $userID)
            ->where('category_id', $categoryID)
            ->orderBy('id', 'desc')
            ->get();
    }
}

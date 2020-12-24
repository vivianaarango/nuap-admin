<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

/**
 * Interface DbProductRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface DbProductRepositoryInterface
{
    /**
     * @param int $productID
     * @param bool $status
     * @return Product
     */
    public function changeStatus(int $productID, bool $status): Product;

    /**
     * @param int $productID
     * @return Product
     */
    public function findByID(int $productID): Product;

    /**
     * @param int $productID
     * @return bool
     */
    public function delete(int $productID): bool;

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
    ): Product;
}
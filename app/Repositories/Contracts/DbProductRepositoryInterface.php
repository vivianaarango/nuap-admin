<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

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
     * @param int $categoryID
     * @return Category
     */
    public function findCategoryByID(int $categoryID): Category;

    /**
     * @param int $userID
     * @return Collection
     */
    public function findApprovedProducts(int $userID): Collection;

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
     * @param bool $status
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
        bool $status,
        string $image = null
    ): Product;

    /**
     * @param int $categoryID
     * @param int $userID
     * @return Collection
     */
    public function findByCategoryAndUserID(int $categoryID, int $userID): Collection;

    /**
     * @param string $userType
     * @return Collection
     */
    public function getEnabledCategories(string $userType): Collection;

    /**
     * @return Collection
     */
    public function getEnabledStoresByDistributor(): Collection;

    /**
     * @return Collection
     */
    public function getEnabledStoresByCommerce(): Collection;

    /**
     * @param array $usersID
     * @return Collection
     */
    public function getSalesByUserID(array $usersID): Collection;

    /**
     * @param array $usersID
     * @return Collection
     */
    public function getFeaturedByUserID(array $usersID): Collection;
}
<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Commerce;
use App\Models\Distributor;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
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
     * @param int $categoryID
     * @return Category
     */
    public function findCategoryByID(int $categoryID): Category
    {
        return Category::findOrFail($categoryID);
    }

    /**
     * @param int $userID
     * @return Collection
     */
    public function findApprovedProducts(int $userID): Collection
    {
        return Product::where('user_id', $userID)
            ->where('status', Payment::STATUS_APPROVED)
            ->get();
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
        $product->status = $status;

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

    /**
     * @param string $userType
     * @return Collection
     */
    public function getEnabledCategories(string $userType): Collection
    {
        return Category::select('categories.*')
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.role', $userType)
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->where('categories.parent_id', 0)
            ->groupBy('categories.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @return Collection
     */
    public function getEnabledStoresByDistributor(): Collection
    {
        return Distributor::select('distributors.*', 'users.*')
            ->join('users', 'distributors.user_id', '=', 'users.id')
            ->join('products', 'products.user_id', '=', 'users.id')
            ->where('users.role', User::DISTRIBUTOR_ROLE)
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->groupBy('distributors.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @return Collection
     */
    public function getEnabledStoresByCommerce(): Collection
    {
        return Commerce::select('commerces.*', 'users.*')
            ->join('users', 'distributors.user_id', '=', 'users.id')
            ->join('products', 'products.user_id', '=', 'users.id')
            ->where('users.role', User::COMMERCE_ROLE)
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->groupBy('commerces.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @param array $usersID
     * @return Collection
     */
    public function getSalesByUserID(array $usersID): Collection
    {
        return Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->where('products.has_special_price', 1)
            ->whereIn('products.user_id', $usersID)
            ->groupBy('products.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @param array $usersID
     * @param array $categoriesID
     * @return Collection
     */
    public function getSalesByUserIDAndCategoryID(array $usersID, array $categoriesID): Collection
    {
        return Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->whereIn('products.category_id', $categoriesID)
            ->whereIn('products.user_id', $usersID)
            ->groupBy('products.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @param int $storeID
     * @return Collection
     */
    public function getProductsByStore(int $storeID): Collection
    {
        return Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->where('products.user_id', $storeID)
            ->groupBy('products.id')
            ->inRandomOrder()
            ->get();
    }

    /**
     * @param array $usersID
     * @return Collection
     */
    public function getFeaturedByUserID(array $usersID): Collection
    {
        return Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.status', User::STATUS_ACTIVE)
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('products.stock', '>', 0)
            ->where('products.is_featured', 1)
            ->whereIn('products.user_id', $usersID)
            ->groupBy('products.id')
            ->inRandomOrder()
            ->get();
    }
}

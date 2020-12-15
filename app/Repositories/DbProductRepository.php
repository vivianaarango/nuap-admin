<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use Exception;

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
}

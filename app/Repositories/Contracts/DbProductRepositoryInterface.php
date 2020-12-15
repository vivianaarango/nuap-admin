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
}
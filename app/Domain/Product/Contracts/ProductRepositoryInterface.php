<?php

namespace App\Domain\Product\Contracts;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\ProductId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function findById(ProductId $id): ?Product;
    public function save(Product $product): void;
    public function delete(Product $product): void;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findByCriteria(array $criteria): array;
}
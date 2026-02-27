<?php

namespace App\Application\Product\Services;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\ProductName;
use App\Domain\Product\ValueObjects\ProductDescription;
use App\Domain\Product\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function createProduct(
        string $name,
        ?string $description,
        ?string $preview,
        int $countryId,
        int $userId,
        array $tagIds = []
    ): Product {
        $product = Product::create(
            new ProductName($name),
            new ProductDescription($description),
            $preview,
            $countryId,
            $userId,
            $tagIds
        );

        $this->productRepository->save($product);
        
        return $product;
    }

    public function updateProduct(
        int $id,
        array $data
    ): ?Product {
        $product = $this->productRepository->findById(new \App\Domain\Product\ValueObjects\ProductId($id));
        
        if (!$product) {
            return null;
        }

        if (isset($data['name'])) {
            $product->updateName(new ProductName($data['name']));
        }

        if (isset($data['description'])) {
            $product->updateDescription(new ProductDescription($data['description']));
        }

        if (array_key_exists('preview', $data)) {
            $product->updatePreview($data['preview']);
        }

        if (isset($data['country_id'])) {
            $product->updateCountry($data['country_id']);
        }

        if (isset($data['tag_ids'])) {
            foreach ($product->getTagIds() as $tagId) {
                $product->removeTag($tagId);
            }
            foreach ($data['tag_ids'] as $tagId) {
                $product->addTag($tagId);
            }
        }

        $this->productRepository->save($product);
        
        return $product;
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->findById(new \App\Domain\Product\ValueObjects\ProductId($id));
    }

    public function getProductsPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage, $filters);
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->findById(new \App\Domain\Product\ValueObjects\ProductId($id));
        
        if (!$product) {
            return false;
        }

        $this->productRepository->delete($product);
        return true;
    }
}
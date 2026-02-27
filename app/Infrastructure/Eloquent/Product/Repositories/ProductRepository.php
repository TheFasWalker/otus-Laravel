<?php

namespace App\Infrastructure\Eloquent\Product\Repositories;

use App\Domain\Product\Contracts\ProductRepositoryInterface;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\ProductId;
use App\Infrastructure\Eloquent\Product\Models\ProductModel;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(ProductId $id): ?Product
    {
        $model = ProductModel::with('tags')->find($id->getValue());
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(Product $product): void
    {
        $data = [
            'name' => $product->getName()->getValue(),
            'description' => $product->getDescription()->getValue(),
            'preview' => $product->getPreview(),
            'country_id' => $product->getCountryId(),
            'user_id' => $product->getUserId(),
            'created_at' => $product->getCreatedAt(),
            'updated_at' => $product->getUpdatedAt(),
        ];

        if ($product->getId()->getValue() > 0) {
            $model = ProductModel::find($product->getId()->getValue());
            $model->update($data);
        } else {
            $model = ProductModel::create($data);
            
            // Устанавливаем ID в сущность
            $reflection = new \ReflectionClass($product);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($product, new ProductId($model->id));
        }

        // Сохраняем теги
        if (!empty($product->getTagIds())) {
            $model->tags()->sync($product->getTagIds());
        }
    }

    public function delete(Product $product): void
    {
        ProductModel::destroy($product->getId()->getValue());
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = ProductModel::with(['tags', 'country', 'author']);
        
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        
        if (isset($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }
        
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $paginator = $query->paginate($perPage);
        
        $paginator->getCollection()->transform(function ($model) {
            return $this->toEntity($model);
        });

        return $paginator;
    }

    public function findByCriteria(array $criteria): array
    {
        $query = ProductModel::with('tags');
        
        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->get()->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    private function toEntity(ProductModel $model): Product
    {
        return Product::reconstruct(
            $model->id,
            $model->name,
            $model->description,
            $model->preview,
            $model->country_id,
            $model->user_id,
            $model->tags->pluck('id')->toArray(),
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable()
        );
    }
}
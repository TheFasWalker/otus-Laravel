<?php 

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepo;
use Illuminate\Support\Facades\Cache;

class ProductService 
{
    private const CACHE_KEY = 'product_list';
    private const CACHE_TTL = '10';
    
    public function __construct(
        private ProductRepo $productRepo
    )
    {
    }
    public function getAllProducts()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->productRepo->getAllProducts();
        });
    }
    
    public function deleteProductById(int $id):bool
    {
        $result = $this->productRepo->deleteProductById($id);
        if ($result) {
            $this->clearProductsCache();
        }
        
        return $result;
    }

    public function createProduct(array $data):Product
    {
        $product = $this->productRepo->createProduct($data);
        
        if(!empty($data['tags'])){
            $product->tags()->attach($data['tags']);
        }

        $this->clearProductsCache();

        return $product;
    
    }

    public function updateProduct(Product $product, array $data):Product
    {
        $this->productRepo->updateProduct($product, $data);
                if (isset($data['tags'])) {
            $product->tags()->sync($data['tags']);
        } else {
            $product->tags()->detach();
        }
        $this->clearProductsCache();
        return $product->fresh()->load('tags');
    }

    public function getAllProductsWithoutCache()
    {
        return $this->productRepo->getAllProducts();
    }

    public function clearProductsCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    
}
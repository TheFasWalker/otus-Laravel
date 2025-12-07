<?php 

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepo;

class ProductService 
{
    public function __construct(
        private ProductRepo $productRepo
    )
    {
    }
    public function getAllProducts()
    {
        return $this->productRepo->getAllProducts();
    }
    
    public function deleteProductById(int $id):bool
    {
        return $this->productRepo->deleteProductById($id);
    }

    public function createProduct(array $data):Product
    {
        $product = $this->productRepo->createProduct($data);
        
        if(!empty($data['tags'])){
            $product->tags()->attach($data['tags']);
        }

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
        return $product->fresh()->load('tags');
    }
    
}
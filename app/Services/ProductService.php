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

    
}
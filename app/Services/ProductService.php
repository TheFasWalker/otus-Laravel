<?php 

namespace App\Services;

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

    
}
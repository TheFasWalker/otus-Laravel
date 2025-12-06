<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepo
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function deleteProductById(int $id):bool
    {
        $productToDelete = Product::findOrFail($id);
        return $productToDelete->delete();
    }

}
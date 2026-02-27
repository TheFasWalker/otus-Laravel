<?php

namespace App\Domain\Product\Events;

use App\Domain\Product\Entities\Product;

class ProductCreated
{
    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
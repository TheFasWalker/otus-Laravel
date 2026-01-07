<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private ProductService $productService;

    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }
    public function index (){
        $products = $this->productService->getAllProducts();
        $title='Заголовок каталога';
        return view('pages.CatalogPage', compact('title', 'products'));
    }
}

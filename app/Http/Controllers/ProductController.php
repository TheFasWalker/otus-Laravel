<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Country;
use App\Models\Product;
use App\Models\Tag;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(
        ProductService $productService
    )
    {
       $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allProducts = $this->productService->getAllProducts();
        return view('pages.admin.products.index', compact('allProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $tags = Tag::all();
        return view('pages.admin.products.create',compact('countries','tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $data= $request->validated();
        $createProduct = $this->productService->createProduct($data);
        if($createProduct){
            return(redirect()->route('admin.products')->with('success','Товар создан успешно'));
        }
        return redirect()->back()->with('error','Произошла ошибка создания товара')->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $tags = Tag::all();
        $countries = Country::all();
        return view('pages.admin.products.edit',compact('product','tags','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            
            $this->productService->updateProduct($product, $data);
            
            return redirect()->route('admin.products')
                ->with('success', 'Продукт успешно обновлен!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при обновлении продукта: ' . $e->getMessage());
            
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        try{
            $this->productService->deleteProductById($id);
            return redirect()->route('admin.products')->with('success','Товар успешно удалён');

        }catch (\Exception $e){
            return redirect()->back()->whith('error',$e->getMessage());
        }
    }
}

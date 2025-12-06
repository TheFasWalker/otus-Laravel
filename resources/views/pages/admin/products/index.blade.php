@extends('layouts.adminLayOut')
@section('content')
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <div class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <div class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 grid grid-cols-[250px_150px_150px_1fr_150px] items-center ">
                  <span class="font-bold p-2">Название продукта</span>
                  <span class="font-bold p-2"> старана<br>производитель</span>
                  <span class="font-bold p-2">Тэги</span>
                  <span class="font-bold p-2">Описание</span>
                  <div class="flex items-center justify-center">
                    <a href="#">Добавить продукт</a>
                  </div>
                </div>
                @foreach ($allProducts as $product)
                  <div class="grid grid-cols-[250px_150px_150px_1fr_150px] border-b dark:border-gray-700 p-2">
                    <span class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white  overflow-hidden">{{ $product->name }}</span> 
                    <span class="px-4 py-3">{{ $product->country->reduction}}</span> 
                    <div class=" flex flex-wrap gap-1">
                      @foreach ( $product->tags  as $tag)
                        <span class="bg-gray-400 rounded-lg h-fit p-1 text-white">{{ $tag->name }}</span>
                      @endforeach
                    </div>
                    <span class="px-4 py-3">{{ $product->description}}</span> 
                    <div class="grid grid-cols-2 items-center ">
                      <a href="#">edit</a>
                      <form 
                      action="#" 
                      method="POST" >
                        @method('DELETE')
                        @csrf
                      <button >delete</button>

                      </form>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
        </div>
    </div>
    </section>
@endsection
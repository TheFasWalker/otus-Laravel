@extends('layouts.adminLayOut')
@section('content')

<section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Создание тэга</h2>
                <form action="{{ route('admin.product.store') }}" method="POST" class=" flex flex-col gap-2">
                        @csrf
                        <div class="grid gap-4 grid-cols-[200px_1fr] sm:gap-6">

                                <label class=" mb-2 text-sm font-medium text-gray-900 dark:text-white flex flex-col gap-1">
                                        Название Тэга
                                        <input 
                                        type="text" 
                                        name="name" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Название тэга" 
                                        required="">
                                </label>  
                                <label class=" mb-2 text-sm font-medium text-gray-900 dark:text-white flex flex-col gap-1">
                                        Краткое описание
                                        <input 
                                        type="text" 
                                        name="description" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Краткое описание" 
                                        required="">
                                </label>  
                                <label class=" mb-2 text-sm font-medium text-gray-900 dark:text-white flex flex-col gap-1">
                                        Ссылка на превью
                                        <input 
                                        type="text" 
                                        name="preview" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Ссылка на превью" 
                                        >
                                </label>  
                                  <label  class="block mb-2.5 text-sm font-medium text-heading">
                                    <span>Выберите старану</span>
                                    <select name='country_id'  class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                                      @foreach ($countries as $country )
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                      @endforeach
                                    </select>
                                  </label>

                                    <label  class="block mb-2.5 text-sm font-medium text-heading">
                                     Выбор тэгов
                                      <select multiple name="tags[]" id="tags" class="block w-full bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                                        @foreach ($tags as $tag )
                                          <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach

                                      </select>
                                    </label>
                                  @if ($errors->any())
                                      <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg">
                                          <ul>
                                              @foreach ($errors->all() as $error)
                                                  <li>{{ $error }}</li>
                                              @endforeach
                                          </ul>
                                      </div>
                                  @endif
                        </div>
                        <button type="submit" 
                        class=" rounded-lgtext-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        Создать продукт
                        </button>
                </form>
        </div>
</section>

@endsection
@extends('layouts.adminLayOut')
@section('content')
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <div class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <div class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 grid grid-cols-[150px_150px_1fr_150px] items-center ">
                  <span class="font-bold p-2">Название страны</span>
                  <span class="font-bold p-2"> Сокращение</span>
                  <span class="font-bold p-2"> Описание</span>
                  <div class="flex items-center justify-center">
                    <a href="{{ route('admin.country.create') }}">Добавить страну</a>
                  </div>
                </div>
                @foreach ($allCountries as $country )
                  <div class="grid grid-cols-[150px_150px_1fr_100px] border-b dark:border-gray-700 p-2">
                    <span class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white "> {{ $country->name }}</span> 
                    <span class="px-4 py-3">{{ $country->reduction }}</span> 
                    <span class="px-4 py-3">{{ $country->description }}</span> 
                    <div class="grid grid-cols-2 items-center ">
                      <a href="{{ route('admin.country.edit', $country->id) }}">edit</a>
                      <form action="{{ route('admin.country.delete', $country->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button>delete</button>
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
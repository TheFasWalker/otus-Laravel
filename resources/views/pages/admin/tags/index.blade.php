@extends('layouts.adminLayOut')
@section('content')
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <div class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <div class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 grid grid-cols-[150px_1fr_150px] items-center ">
                  <span class="font-bold p-2">Название тэга</span>
                  <span class="font-bold p-2"> Описание</span>
                  <div class="flex items-center justify-center">
                  @can('only-admin')
                      <a href="{{ route('admin.tags.create') }}">Добавить тэг</a>
                  @endcan

                  </div>
                </div>
                @foreach ($tags as $tag)
                                  <div class="grid grid-cols-[150px_1fr_100px] border-b dark:border-gray-700 p-2">
                  <span class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white "> {{ $tag->name }}</span> 
                  <span class="px-4 py-3">{{ $tag->description }}</span> 
                  <div class="grid grid-cols-2 items-center ">
                    @can('only-admin')
                       <a href="{{ route('admin.tag.edit', $tag->id) }}">edit</a>
                    <form 
                    action="{{ route('admin.tag.delete',$tag->id) }}" 
                    method="POST" >
                      @method('DELETE')
                      @csrf
                                            <button >delete</button>

                    </form>
                    @endcan
                   
                  </div>
                </div>
                @endforeach
              </div>
            </div>
        </div>
    </div>
    </section>
@endsection
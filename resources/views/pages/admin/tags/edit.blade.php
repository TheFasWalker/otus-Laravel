@extends('layouts.adminLayOut')
@section('content')

<section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Редактирвоание тэга</h2>
                <form action="{{ route('admin.tag.update', $tag->id) }}" 
                        method="POST"
                class=" flex flex-col gap-2"
                >
                @csrf
                @method('PUT')
                        <div class="grid gap-4 grid-cols-[200px_1fr] sm:gap-6">

                                <label class=" mb-2 text-sm font-medium text-gray-900 dark:text-white flex flex-col gap-1">
                                        Название Тэга
                                        <input 
                                        type="text" 
                                        name="name" 
                                        value="{{ $tag->name }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Название тэга" 
                                        required="">
                                </label>

                                <label class=" mb-2 text-sm font-medium text-gray-900 dark:text-white flex flex-col gap-1">
                                        Название Тэга
                                        <input 
                                        type="text" 
                                        name="description" 
                                        value="{{ $tag->description }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Название тэга" 
                                        required="">
                                </label>
                        </div>
                        <button type="submit" 
                        class=" rounded-lgtext-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        Сохранить изменения
                        </button>
                </form>
        </div>
</section>

@endsection
@extends('layouts.mainLayout')
@section('content')
    <h1>{{ $title }}</h1>
    <div class="grid grid-cols-3 gap-2">
        @foreach ($products as $product)
            <div class="border p-2 rounded-lg bg-gray-200 flex flex-col gap-1 ">
                <div class="aspect-[2/1] w-full items-center overflow-hidden">
                    @if ($product->preview)
                            <img 
                            class="w-full h-full object-cover"
                            src="{{ $product->preview }}" alt=""
                            >                        
                    @else
                    <div class=" flex items-center justify-center h-full w-full border">
                        <h2>No Image</h2>
                    </div>
                    @endif
                </div>
                
                <h2 class="text-lg">{{$product->name}}</h2>
                <p class=" text-sm">{{$product->description}}</p>

            </div>
        @endforeach
        
    </div>
    
@endsection
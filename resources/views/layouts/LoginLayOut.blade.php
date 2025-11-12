<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])


    </head>
    <body class="flex items-center justify-center w-screen h-screen relative">
        <a
        class="absolute top-0 left-0 ml-3 p-2 text-base font-medium text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group" 
        href="/">на главную</a>
                @yield("content")
    </body>
    </html>
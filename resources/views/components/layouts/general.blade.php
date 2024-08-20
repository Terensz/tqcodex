<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('Elszámolási rendszer') }}</title>

    <tallstackui:script />

    <link href="/css/tailwind/tailwind.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @livewireScripts
    @include('flatpickr::components.style')
    @include('flatpickr::components.script')

    <style>
    /* ul { list-style-type: disc !important;} */
    </style>
    @trixassets
    <!-- trixassets -->


    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
  </head>
  <body class="h-full font-sans antialiased">
    @yield('body')
  </body>
</html>

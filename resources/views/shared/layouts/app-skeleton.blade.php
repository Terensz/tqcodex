<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('project.ProjectTitle') }}</title>

    <tallstackui:script />

    <!-- <link href="/css/tailwind/tailwind.min.css" rel="stylesheet"> -->
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles
    @include('flatpickr::components.style')

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
  </head>
  <body class="font-sans antialiased">
    @yield('body')
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @livewireScripts
    @include('flatpickr::components.script')
    <!-- trixassets -->
    @trixassets
  </body>
</html>

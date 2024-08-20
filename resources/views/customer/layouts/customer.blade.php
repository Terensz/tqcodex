{{-- customer.blade.php --}}
@extends('components.layouts.general')

@section('body')
    <div x-data="{ open: false }" @keydown.window.escape="open = false">
        {{-- Customer mobile menu --}}
        @include('customer.layouts.mobile-menu')

        {{-- Customer sidebar --}}
        @include('customer.sidebar.desktop-sidebar')
        {{-- Page Content --}}
        <main class="py-1 lg:pl-72">
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </div>
        </main>
    </div>
    <x-toaster-hub />
    <script>
    document.addEventListener("DOMContentLoaded", () => {
    //Toaster.success('alma');
    @if(isset($toasts) && !empty($toasts) && is_array($toasts)) 
        @foreach($toasts as $toast)
            Toaster.{{ $toast['type'] }}("{{ $toast['message'] }}");
        @endforeach
    @endif
    });
    </script>
@endsection
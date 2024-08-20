{{-- customer.blade.php --}}
@extends('components.layouts.general')

@section('body')
    <div x-data="{ open: false }" @keydown.window.escape="open = false">
        {{-- Page Content --}}
        <main class="py-1">
            <div class="px-4 sm:px-6">
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
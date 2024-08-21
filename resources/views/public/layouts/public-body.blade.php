{{-- public/layouts/public-body.blade.php --}}
@extends('shared.layouts.app-skeleton')

@section('body')
    <div class="h-full v-full">
        @yield('content')
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
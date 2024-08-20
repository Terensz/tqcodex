@php 
if (!isset($backgroundColor)) {
    $backgroundColor = 'blue';
}
$classes = 'inline-flex items-center px-4 py-2 bg-'.$backgroundColor.'-500 border border-transparent rounded-md font-semibold text-white hover:bg-'.$backgroundColor.'-700 focus:outline-none focus:ring focus:border-'.$backgroundColor.'-700 active:bg-'.$backgroundColor.'-800';
@endphp

<a {{ $wire }} class="{{ $classes }}">
    <x-icon name="{{ $icon }}" class="h-5 w-5 mr-1" />
    {{ $text }}
</a>
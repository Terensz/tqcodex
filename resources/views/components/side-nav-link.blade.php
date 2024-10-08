@props(['active'])

@php
$classes = ($active ?? false)
    ? 'bg-gray-800 text-white group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
    : 'text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
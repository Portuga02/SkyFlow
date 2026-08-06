@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold leading-5 text-brand-700 bg-brand-100 transition duration-150 ease-in-out'
            : 'inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium leading-5 text-gray-500 hover:text-brand-700 hover:bg-brand-50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

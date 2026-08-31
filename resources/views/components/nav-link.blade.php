@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold leading-5 text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-slate-700/80 shadow-2xs transition-all duration-150 ease-in-out'
            : 'inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium leading-5 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 transition-all duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
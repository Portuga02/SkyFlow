@props(['disabled' => false])

@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-brand-500 dark:focus:border-brand-500 focus:ring-brand-500 dark:focus:ring-brand-500 rounded-md shadow-sm transition-colors duration-300']) !!}>

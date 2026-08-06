@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-brand-200 bg-white focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm placeholder:text-gray-400']) !!}>

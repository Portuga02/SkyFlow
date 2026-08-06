@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-brand-900 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>

@props(['iconOnly' => false])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 select-none']) }}>
    <span
        class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-700 shadow-soft">
        <i class="fa-solid fa-cloud-bolt text-white text-base"></i>
    </span>
    @unless ($iconOnly)
        <span class="text-lg font-extrabold tracking-tight text-brand-900">
            Sky<span class="text-brand-500">Flow</span>
        </span>
    @endunless
</div>
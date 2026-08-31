@props(['type' => 'button'])

<button {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 bg-white text-brand-700 hover:text-brand-900 border border-brand-200 hover:border-brand-300 rounded-xl font-medium text-sm shadow-xs hover:bg-brand-50/60 active:bg-brand-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none transition-all duration-150 ease-in-out select-none'
]) }}>
    {{ $slot }}
</button>
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 font-medium text-xs text-emerald-400 shadow-sm transition-colors duration-300']) }}>
        <i class="fa-solid fa-circle-check shrink-0"></i>
        <span>{{ $status }}</span>
    </div>
@endif
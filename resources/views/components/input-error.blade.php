@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs font-medium text-rose-600 dark:text-rose-400 space-y-1 mt-1.5 transition-colors duration-300']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation shrink-0 text-[11px]"></i>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
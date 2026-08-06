@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-rose-600 space-y-1 mt-2']) }}>
        @foreach ((array) $messages as $message)
            <li><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</li>
        @endforeach
    </ul>
@endif

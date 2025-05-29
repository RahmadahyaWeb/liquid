@props([
    'type' => 'button',
    'block' => false,
])

@php
    $baseClass =
        'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2';

    if ($block) {
        $baseClass .= ' w-full';
    }
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClass]) }}>
    {{ $slot }}
</button>

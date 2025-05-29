<div>
    @if ($label ?? false)
        <label for="{{ $id ?? 'select' }}" class="block mb-2 text-sm font-medium text-gray-900">
            {{ $label }}
        </label>
    @endif

    <select id="{{ $id ?? 'select' }}" name="{{ $name ?? ($id ?? 'select') }}"
        {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5']) }}>
        {{ $slot }}
    </select>
</div>

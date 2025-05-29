@props(['id', 'type' => 'text', 'label' => null, 'placeholder' => '', 'required' => false, 'value' => ''])

<div>
    @if ($label)
        <label for="{{ $id }}"
            class="block mb-2 text-sm font-medium
                @error($id) text-red-700 @else text-gray-900 @enderror">
            {{ $label }}
        </label>
    @endif

    <input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" value="{{ old($id, $value) }}"
        placeholder="{{ $placeholder }}" @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'block w-full p-2.5 text-sm rounded-lg ' .
                ($errors->has($id)
                    ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500'
                    : 'bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500'),
        ]) }} />

    @error($id)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>

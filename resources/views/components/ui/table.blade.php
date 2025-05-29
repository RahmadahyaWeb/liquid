@props(['columns', 'rows', 'canDelete' => null])

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                @foreach ($columns as $field => $label)
                    <th scope="col" class="px-6 py-3">{{ $label }}</th>
                @endforeach
                <th scope="col" class="px-6 py-3"><span class="sr-only">Edit</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr class="bg-white border-b border-gray-200">
                    @foreach ($columns as $field => $label)
                        @php
                            if (str_contains($field, '.')) {
                                [$relation, $relField] = explode('.', $field);
                                $related = $row->{$relation};

                                if ($related instanceof \Illuminate\Support\Collection) {
                                    // relasi many (collection)
                                    $value = $related->pluck($relField)->implode(', ');
                                } else {
                                    // relasi one (object)
                                    $value = $related->{$relField} ?? '';
                                }
                            } else {
                                $value = is_object($row) ? $row->{$field} ?? '' : $row[$field] ?? '';
                            }
                        @endphp

                        @if ($loop->first)
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $value }}
                            </th>
                        @else
                            <td class="px-6 py-4">{{ $value }}</td>
                        @endif
                    @endforeach
                    <td class="px-6 flex gap-3 justify-end py-4 text-nowrap">
                        <a href="#" class="font-medium text-blue-600 hover:underline"
                            wire:click="edit({{ $row->id }})">
                            Edit
                        </a>
                        @if (is_null($canDelete) || call_user_func($canDelete, $row))
                            <a href="#" class="font-medium text-red-600 hover:underline"
                                wire:click="delete({{ $row->id }})" wire:confirm="Yakin ingin hapus data?">
                                Delete
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($rows->links())
    <div class="py-4">
        {{ $rows->links() }}
    </div>
@endif

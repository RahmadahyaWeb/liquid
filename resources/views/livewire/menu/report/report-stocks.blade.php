<div>
    <x-slot name="header">Laporan Stok</x-slot>

    <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow-sm ">
        <form wire:submit.prevent="download">
            {{-- <div class="grid grid-cols-2 gap-2">
                <x-form.input type="date" id="from_date" label="Dari Tanggal" wire:model="from_date" />
                <x-form.input type="date" id="to_date" label="Sampai Tanggal" wire:model="to_date" />
            </div> --}}
            <x-form.select id="warehouseId" name="warehouseId" wire:model="warehouseId" label="Gudang">
                <option value="">Pilih Gudang</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->nama_gudang }}</option>
                @endforeach
            </x-form.select>


            <div class="mt-3">
                <x-ui.button type="submit">Download</x-ui.button>
            </div>
        </form>
    </div>

</div>

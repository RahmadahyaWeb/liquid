<div>
    <x-slot name="header">Laporan Penjualan</x-slot>

    <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow-sm ">
        <form wire:submit.prevent="download">
            <div class="grid grid-cols-2 gap-2">
                <x-form.input type="date" id="fromDate" label="Dari Tanggal" wire:model="fromDate" />
                <x-form.input type="date" id="toDate" label="Sampai Tanggal" wire:model="toDate" />
            </div>

            <div class="mt-3">
                <x-ui.button type="submit">Download</x-ui.button>
            </div>
        </form>
    </div>

</div>

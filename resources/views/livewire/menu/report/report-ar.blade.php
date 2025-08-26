<div>
    <x-slot name="header">Laporan AR</x-slot>

    <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow-sm ">
        <form wire:submit.prevent="download">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <x-form.input type="date" id="from_date" label="Dari Tanggal" wire:model="from_date" />
                <x-form.input type="date" id="to_date" label="Sampai Tanggal" wire:model="to_date" />
            </div>

            <x-form.select id="status" name="status" wire:model="status" label="Status AR">
                <option value="">Pilih Status</option>
                <option value="OPEN">OPEN</option>
                <option value="PARTIAL">PARTIAL</option>
                <option value="PAID">PAID</option>
                <option value="OVERDUE">OVERDUE</option>
            </x-form.select>


            <div class="mt-3">
                <x-ui.button type="submit">Download</x-ui.button>
            </div>
        </form>
    </div>
</div>

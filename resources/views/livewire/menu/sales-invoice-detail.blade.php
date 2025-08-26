<div>
    <x-slot name="header">Detail Data Invoice</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <div>
            <x-ui.button wire:click="print">Cetak Invoice</x-ui.button>
        </div>
    </div>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :cellClass="$cellClass" :canEdit="$canEdit"
        :canDelete="$canDelete" />
</div>

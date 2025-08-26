<div>
    <x-slot name="header">Data Fifo Layer</x-slot>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :cellClass="$cellClass" :canEdit="$canEdit"
        :canDelete="$canDelete" />
</div>

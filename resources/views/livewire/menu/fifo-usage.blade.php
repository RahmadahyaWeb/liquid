<div>
    <x-slot name="header">Data Fifo Usage</x-slot>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :canEdit="$canEdit" :canDelete="$canDelete" />
</div>

<div>
    <x-slot name="header">Data AR</x-slot>

    <x-ui.table :columns="$columns" :rows="$rows" :canEdit="$canEdit" :canDelete="$canDelete" :columnFormats="$columnFormats"
        :cellClass="$cellClass" />
</div>

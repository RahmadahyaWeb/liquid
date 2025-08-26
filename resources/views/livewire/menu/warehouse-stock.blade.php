<div>
    <x-slot name="header">Data Stok</x-slot>

    <x-ui.table :columns="$columns" :rows="$rows" :canEdit="$canEdit" :canDelete="$canDelete" />
</div>

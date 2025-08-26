<div>
    <x-slot name="header">Data Invoice</x-slot>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :cellClass="$cellClass" :canEdit="$canEdit"
        :canDelete="$canDelete" :actions="[
            [
                'label' => 'Lihat Detail',
                'route' => fn($row) => route('sales-management.sales-invoices-detail', $row->id),
                'method' => 'GET',
                // 'target' => '_blank',
            ],
        ]" />
</div>

<div>
    <x-slot name="header">Data Pembelian</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input type="date" id="editing.tanggal_pembelian" label="Tanggal Pembelian"
            wire:model="editing.tanggal_pembelian" />

        <x-form.select id="editing.warehouse_id" label="Gudang" wire:model="editing.warehouse_id">
            <option value="">Pilih Gudang</option>

            @foreach ($warehousesGroup as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->nama_gudang }}</option>
            @endforeach
        </x-form.select>

        <x-form.select id="editing.supplier_id" label="Supplier" wire:model="editing.supplier_id">
            <option value="">Pilih Supplier</option>

            @foreach ($suppliersGroup as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
            @endforeach
        </x-form.select>

        @if ($modalMethod == 'save')
            <x-form.select id="editing.status" name="editing.status" label="Status" wire:model="editing.status">
                <option value="">Pilih Status</option>
                <option value="draft">Draft</option>
                <option value="final">Final</option>
            </x-form.select>
        @endif
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :canEdit="$canEdit" :canDelete="$canDelete"
        :cellClass="$cellClass" :actions="[
            [
                'label' => 'Lihat Detail',
                'route' => fn($row) => route('purchase-management.purchases-detail', $row->id),
                'method' => 'GET',
                // 'target' => '_blank',
            ],
        ]" />

</div>

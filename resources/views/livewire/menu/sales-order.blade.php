<div>
    <x-slot name="header">Data Sales Order</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input type="date" id="editing.tanggal" label="Tanggal" wire:model="editing.tanggal" />

        <x-form.select id="editing.warehouse_id" label="Gudang" wire:model="editing.warehouse_id">
            <option value="">Pilih Gudang</option>

            @foreach ($warehousesGroup as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->nama_gudang }}</option>
            @endforeach
        </x-form.select>

        <x-form.select id="editing.customer_id" label="Pelanggan" wire:model="editing.customer_id">
            <option value="">Pilih Pelanggan</option>

            @foreach ($customersGroup as $customer)
                <option value="{{ $customer->id }}">
                    {{ $customer->nama_pelanggan }} | {{ $customer->kode_pelanggan }}
                </option>
            @endforeach
        </x-form.select>

        @if ($modalMethod == 'save')
            <x-form.select id="editing.status" name="editing.status" label="Status" wire:model="editing.status">
                <option value="">Pilih Status</option>
                <option value="draft">Draft</option>
                @hasanyrole(['admin'])
                    <option value="approved">Approved</option>
                @endhasanyrole
            </x-form.select>
        @endif

        <x-form.input id="editing.catatan" label="Catatan" wire:model="editing.catatan" />
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :cellClass="$cellClass" :canEdit="$canEdit"
        :canDelete="$canDelete" :actions="[
            [
                'label' => 'Lihat Detail',
                'route' => fn($row) => route('sales-management.sales-orders-detail', $row->id),
                'method' => 'GET',
                // 'target' => '_blank',
            ],
        ]" />
</div>

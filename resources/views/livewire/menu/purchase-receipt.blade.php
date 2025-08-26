<div>
    <x-slot name="header">Data Penerimaan</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input type="date" id="editing.tanggal_penerimaan" label="Tanggal Penerimaan"
            wire:model="editing.tanggal_penerimaan" />

        <x-form.select id="editing.purchase_id" label="Nomor Pembelian" wire:model="editing.purchase_id">
            <option value="">Pilih Nomor Pembelian</option>

            @foreach ($purchasesGroup as $purchase)
                <option value="{{ $purchase->id }}">{{ $purchase->nomor_pembelian }}</option>
            @endforeach
        </x-form.select>
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :canEdit="$canEdit" :canDelete="$canDelete" :cellClass="$cellClass"
        :actions="[
            [
                'label' => 'Lihat Detail',
                'route' => fn($row) => route('purchase-management.receipts-detail', $row->id),
                'method' => 'GET',
                // 'target' => '_blank',
            ],
        ]" />
</div>

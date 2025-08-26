<div>
    <x-slot name="header">Detail Data Penerimaan</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        @if ($receiptHeader->purchase->status != 'received')
            <x-ui.button wire:click="updateReceiptStatus" wire:confirm="Yakin ingin selesai?">Selesai</x-ui.button>
        @else
            <x-ui.button wire:click="rollback" wire:confirm="Yakin ingin rollback?">Rollback</x-ui.button>
        @endif
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.nama_produk" label="Nama Produk" wire:model="editing.nama_produk" disabled />
        <x-form.input id="editing.qty" label="Qty Beli" wire:model="editing.qty" disabled />
        <x-form.input id="editing.qty_diterima" label="Qty Diterima" wire:model="editing.qty_diterima" />

    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :canDelete="$canDelete" :canEdit="$canEdit" />

</div>

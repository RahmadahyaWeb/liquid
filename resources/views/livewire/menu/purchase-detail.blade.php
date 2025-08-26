<div>
    <x-slot name="header">Detail Data Pembelian</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>

        @if ($purchaseHeader->status == 'draft')
            <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
        @endif
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.select id="editing.product_id" label="Produk" wire:model.change="editing.product_id">
            <option value="">Pilih Produk</option>

            @foreach ($productsGroup as $product)
                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
            @endforeach
        </x-form.select>

        <x-form.input type="number" id="editing.qty" label="Qty" wire:model="editing.qty" />
        <x-form.input type="number" id="editing.harga_modal" label="Harga" wire:model="editing.harga_modal"
            disabled />

    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :canEdit="$canEdit" :canDelete="$canDelete" />
</div>

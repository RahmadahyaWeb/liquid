<div>
    <x-slot name="header">Detail Data Sales Order</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <div>
            @if ($soHeader->status != 'invoiced')

                @if ($rows->total() > 0 && $soHeader->status == 'approved')
                    <x-ui.button wire:click="createInvoice">Buat Invoice</x-ui.button>
                @endif

                <x-ui.button wire:click="toggleCrudModal('create', 'true'
                )">Tambah Data</x-ui.button>
            @endif
        </div>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.select id="editing.product_id" label="Produk" wire:model.change="editing.product_id">
            <option value="">Pilih Produk</option>

            @foreach ($productsGroup as $product)
                <option value="{{ $product->id }}">{{ $product->nama_produk }} | {{ $product->kode_produk }}</option>
            @endforeach
        </x-form.select>

        <x-form.input type="number" id="editing.harga_jual" label="Harga Jual" wire:model="editing.harga_jual"
            disabled />
        <x-form.input type="number" id="editing.max_diskon" label="Max Diskon (%)" wire:model="editing.max_diskon"
            disabled />

        <x-form.input type="number" id="editing.qty" label="Qty" wire:model="editing.qty" />
        <x-form.input type="number" id="editing.diskonPercent" label="Diskon (%)" wire:model="editing.diskonPercent" />

    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :cellClass="$cellClass" :canEdit="$canEdit"
        :canDelete="$canDelete" />
</div>

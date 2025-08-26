<div>
    <x-slot name="header">Data Harga Produk</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.select id="editing.product_id" label="Produk" wire:model="editing.product_id">
            <option value="">Pilih Produk</option>

            @foreach ($productsGroup as $product)
                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
            @endforeach
        </x-form.select>

        <x-form.select id="editing.customer_type" name="editing.customer_type" label="Tipe Customer"
            wire:model="editing.customer_type">
            <option value="">Pilih Status</option>
            <option value="B2B">B2B</option>
            <option value="B2C">B2C</option>
        </x-form.select>

        <x-form.input type="number" id="editing.harga_jual" label="Harga Jual" wire:model="editing.harga_jual" />
        <x-form.input type="number" id="editing.max_diskon" label="Max Diskon" wire:model="editing.max_diskon" />

    </x-ui.modal-form>

    <x-ui.table :rows="$rows" :columns="$columns" :columnFormats="$columnFormats" />
</div>

<div>
    <x-slot name="header">Data Produk</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.kode_produk" label="Kode Produk" wire:model="editing.kode_produk" />
        <x-form.input id="editing.nama_produk" label="Nama Produk" wire:model="editing.nama_produk" />
        <x-form.input id="editing.deskripsi" label="Deskripsi Produk" wire:model="editing.deskripsi" />
        <x-form.input type="file" id="editing.foto_produk" label="Foto Produk" wire:model="editing.foto_produk" />

        <x-form.select id="editing.product_category_id" label="Kategori Produk"
            wire:model="editing.product_category_id">
            <option value="">Pilih Kategori</option>

            @foreach ($categoryGroup as $category)
                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
            @endforeach
        </x-form.select>

        <x-form.select id="editing.supplier_id" label="Supplier" wire:model="editing.supplier_id">
            <option value="">Pilih Supplier</option>

            @foreach ($supplierGroup as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
            @endforeach
        </x-form.select>

        <x-form.input type="number" id="editing.harga_beli" label="Harga Beli" wire:model="editing.harga_beli"
            step="0.01" />

        <x-form.select id="editing.status" name="editing.status" label="Status" wire:model="editing.status">
            <option value="">Pilih Status</option>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </x-form.select>
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" />
</div>

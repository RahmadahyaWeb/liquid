<div>
    <x-slot name="header">Data Kategori Produk</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.nama_kategori" label="Nama Kategori" wire:model="editing.nama_kategori" />
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" />
</div>

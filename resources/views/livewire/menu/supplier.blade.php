<div>
    <x-slot name="header">Data Supplier</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.kode_supplier" label="Kode Supplier" wire:model="editing.kode_supplier" />
        <x-form.input id="editing.nama_supplier" label="Nama Supplier" wire:model="editing.nama_supplier" />
        <x-form.input id="editing.kontak" label="Kontak Supplier" wire:model="editing.kontak" />
        <x-form.input id="editing.alamat" label="Alamat Supplier" wire:model="editing.alamat" />

        <x-form.select id="editing.status" name="editing.status" label="Status" wire:model="editing.status">
            <option value="">Pilih Status</option>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </x-form.select>
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" />
</div>

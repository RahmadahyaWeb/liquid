<div>
    <x-slot name="header">Data Gudang</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.nama_gudang" label="Nama Gudang" wire:model="editing.nama_gudang" />

        <x-form.select id="editing.status" name="editing.status" label="Status" wire:model="editing.status">
            <option value="">Pilih Status</option>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </x-form.select>
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" />
</div>

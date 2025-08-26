<div>
    <x-slot name="header">Data Pelanggan</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.nama_pelanggan" label="Nama Pelanggan" wire:model="editing.nama_pelanggan" />
        <x-form.input id="editing.kontak" label="Kontak Pelanggan" wire:model="editing.kontak" />

        <x-form.select id="editing.city_id" label="Kabupaten / Kota" wire:model="editing.city_id">
            <option value="">Pilih Kabupaten / Kota</option>

            @foreach ($cityGroup as $city)
                <option value="{{ $city->id }}">{{ $city->nama_kabupaten }}</option>
            @endforeach
        </x-form.select>

        <x-form.input id="editing.alamat" label="Alamat Pelanggan" wire:model="editing.alamat" />

        <x-form.select id="editing.customer_type" name="editing.customer_type" label="Tipe Customer"
            wire:model="editing.customer_type">
            <option value="">Pilih Status</option>
            <option value="B2B">B2B</option>
            <option value="B2C">B2C</option>
        </x-form.select>

        <x-form.input type="number" id="editing.TOP" label="TOP Pelanggan" wire:model="editing.TOP" />

    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" />
</div>

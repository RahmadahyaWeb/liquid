<div>
    <x-slot name="header">Hak Akses Pengguna</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.name" label="Nama Role" wire:model="editing.name" />

        <div class="max-h-32 overflow-y-auto mt-4 bg-gray-50 border border-gray-300 p-4 rounded-lg space-y-4">
            @foreach ($permissionGroups as $group => $permissions)
                <div>
                    <div class="text-sm font-semibold mb-1 capitalize">{{ $group }}</div>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($permissions as $permission)
                            <div class="flex items-center">
                                <x-form.checkbox id="perm-{{ $permission['id'] }}" value="{{ $permission['id'] }}"
                                    wire:model="editing.permissions" label="{{ ucfirst($permission['action']) }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :canDelete="$canDelete" />
</div>

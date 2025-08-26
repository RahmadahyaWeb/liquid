<div>
    <x-slot name="header">Config</x-slot>

    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input id="editing.config" label="Nama Config" wire:model="editing.config" disabled />
        <x-form.input id="editing.value" label="Value" wire:model="editing.value" />
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :canDelete="$canDelete" />
</div>

<div>
    <x-slot name="header">Data Pembayaran</x-slot>

    <div class="flex justify-between mt-5">
        <div></div>
        <x-ui.button wire:click="toggleCrudModal('create', 'true')">Tambah Data</x-ui.button>
    </div>


    <x-ui.modal-form :title="$modalTitle" :modalMethod="$modalMethod">
        <x-form.input type="date" id="editing.payment_date" label="Tanggal Pembayaran"
            wire:model="editing.payment_date" />

        <x-form.input id="editing.reference_no" label="No Reff" wire:model="editing.reference_no" />

        <x-form.select id="editing.receivable_id" label="Invoice" wire:model.change="editing.receivable_id">
            <option value="">Pilih Invoice</option>

            @foreach ($arGroup as $ar)
                <option value="{{ $ar->id }}">{{ $ar->invoice->no_invoice }}</option>
            @endforeach
        </x-form.select>

        <x-form.input type="number" id="balance" label="Sisa Piutang" wire:model="balance" step="0.01" disabled />

        <x-form.input type="number" id="editing.amount" label="Amount" wire:model="editing.amount" step="0.01" />

        <x-form.select id="editing.method" name="editing.method" label="Metode Pembayaran" wire:model="editing.method">
            <option value="">Pilih Metode Pembayaran</option>
            <option value="CASH">CASH</option>
            <option value="TRANSFER">TRANSFER</option>
        </x-form.select>

        <x-form.input id="editing.notes" label="Notes" wire:model="editing.notes" />
    </x-ui.modal-form>

    <x-ui.table :columns="$columns" :rows="$rows" :columnFormats="$columnFormats" :canEdit="$canEdit" :canDelete="$canDelete" />

</div>

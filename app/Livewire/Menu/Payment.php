<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Payment as ModelsPayment;
use App\Models\Receivable;
use Livewire\Component;

use function PHPSTORM_META\map;

class Payment extends BaseComponent
{
    public $modalTitle = 'Form Pembayaran';

    protected array $permissionMap = [
        'save' => ['edit pembayaran'],
        'edit' => ['edit pembayaran'],
        'delete' => ['delete pembayaran']
    ];

    public $editing =  [
        'id' => '',
        'receivable_id' => '',
        'payment_date' => '',
        'amount' => 0,
        'method' => '',
        'reference_no' => '',
        'notes' => '',
    ];

    public $balance = 0;

    public $arGroup = [];

    public function mount()
    {
        $this->fetchAr();
    }

    public function fetchAr()
    {
        $this->arGroup = Receivable::with(['invoice'])->where('status', '<>', 'PAID')->get();
    }

    public function updatedEditingReceivableId($id)
    {
        $this->balance = Receivable::where('id', $id)->value('balance');
    }

    public function create()
    {
        $this->validate([
            'editing.receivable_id' => 'required',
            'editing.payment_date' => 'required',
            'editing.amount' => 'required',
            'editing.method' => 'required',
        ]);

        $this->executeSave(function () {
            $receivable = Receivable::findOrFail($this->editing['receivable_id']);

            $payment = $receivable->payments()->create([
                'payment_date' => $this->editing['payment_date'],
                'amount' => $this->editing['amount'],
                'method' => $this->editing['method'],
                'reference_no' => $this->editing['reference_no'],
                'notes' => $this->editing['notes'],
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $payment = ModelsPayment::findOrFail($id);

            $receivable = $payment->receivable;

            $payment->delete();
        });
    }

    public function render()
    {
        $rows = ModelsPayment::with(['receivable'])->paginate();

        $columns = [
            'receivable.invoice.no_invoice' => 'No Invoice',
            'receivable.customer.kode_pelanggan' => 'Kode Pelanggan',
            'payment_date' => 'Tanggal Pembayaran',
            'amount' => 'Amount',
            'method' => 'Metode Pembayaran',
            'reference_no' => 'Reff No',
            'notes' => 'Notes'
        ];

        $columnFormats = [
            'amount' => fn($row) => $this->format_rupiah($row->amount),
        ];

        $canEdit = fn($row) => false;
        // jika payment sudah lebih dari 1 hari tidak bisa dihapus
        $canDelete = fn($row) => $row->created_at->diffInDays(now()) < 1;

        return view('livewire.menu.payment', compact(
            'rows',
            'columns',
            'columnFormats',
            'canEdit',
            'canDelete'
        ));
    }
}

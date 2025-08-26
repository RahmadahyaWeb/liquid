<?php

namespace App\Exports;

use App\Models\Receivable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $from_date, $to_date, $status;

    public function __construct($from_date, $to_date, $status)
    {
        $this->from_date = $from_date;
        $this->to_date   = $to_date;
        $this->status    = $status;
    }

    public function collection()
    {
        $query = Receivable::with(['customer', 'invoice'])
            ->join('sales_invoices as invoices', 'receivables.sales_invoice_id', '=', 'invoices.id')
            ->select('receivables.*')
            ->addSelect([
                'payments_sum_amount' => DB::table('payments')
                    ->selectRaw('COALESCE(SUM(amount),0)')
                    ->whereColumn('payments.receivable_id', 'receivables.id')
            ])
            ->orderByDesc('invoices.no_invoice');

        if (!empty($this->status)) {
            $query->where('receivables.status', $this->status);
        }

        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query->whereBetween('invoices.tanggal', [$this->from_date, $this->to_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Kode Pelanggan',
            'Nama Pelanggan',
            'Total Piutang',
            'Total Pembayaran',
            'Sisa Piutang',
            'Tanggal Jatuh Tempo',
            'Status',
        ];
    }

    public function map($row): array
    {
        // Pastikan status selalu up-to-date
        $row->recalcBalance();

        return [
            $row->invoice->no_invoice ?? '',
            $row->customer->kode_pelanggan ?? '',
            $row->customer->nama_pelanggan ?? '',
            number_format($row->amount, 2, ',', '.'),
            number_format($row->payments_sum_amount, 2, ',', '.'),
            number_format($row->balance, 2, ',', '.'),
            $row->due_date,
            $row->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rows = $this->collection();
        $styles = [];

        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;
            if ($row->status === 'OVERDUE') {
                $styles["H{$excelRow}"] = [
                    'font' => ['color' => ['rgb' => 'FF0000']],
                ];
            }
        }

        return $styles;
    }
}

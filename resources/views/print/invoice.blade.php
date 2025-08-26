<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .invoice-box {
            width: 100%;
        }

        .header-table,
        .info-table,
        .items-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td,
        .info-table td,
        .items-table td,
        .summary-table td {
            padding: 5px;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            padding: 5px;
        }

        .items-table td {
            border-bottom: 1px solid #ccc;
            padding: 5px;
        }

        .text-right,
        th.text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .border-top {
            border-top: 1px solid #000;
        }

        .no-border td {
            border: none;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }
    </style>


</head>

<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <h2>{{ $config['nama_toko'] ?? '' }}</h2>
                <p>
                    {{ $config['alamat_toko'] ?? '' }}<br>
                    {{ $config['telepon_toko'] ?? '' }}<br>
                    {{ $config['email_toko'] ?? '' }}
                </p>
            </td>
            <td class="w-half text-right">
                <h2>INVOICE</h2>
                <p>
                    {{ $header->no_invoice }}
                    <br>
                    {{ date('d-m-Y', strtotime($header->tanggal)) }}
                </p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <strong>Kepada:</strong><br>
                {{ $header->customer->kode_pelanggan }} | {{ $header->customer->nama_pelanggan }}<br>
                {{ $header->customer->alamat }} <br>
                {{ $header->customer->kontak }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left">No</th>
                <th style="text-align: left">Kode Produk</th>
                <th style="text-align: left">Nama Produk</th>
                <th style="text-align: left">Qty</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp

            @foreach ($items as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->product->kode_produk }}</td>
                    <td>{{ $item->product->nama_produk }}</td>
                    <td>{{ $item->qty }}</td>
                    <td class="text-right">{{ number_format($item->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="text-right" style="width: 80%;"><strong>Subtotal</strong></td>
            <td class="text-right">
                {{ number_format($header->total_bayar, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="text-right border-top"><strong>Total</strong></td>
            <td class="text-right border-top">
                <strong>{{ number_format($header->total_bayar, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <br><br>

    <p><em>Dokumen ini digenerate secara otomatis oleh sistem dan tidak memerlukan tanda tangan.</em></p>

</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary table {
            width: auto;
            margin-top: 0;
        }
        .summary th, .summary td {
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Aplikasi Billing</p>
        <p>Tanggal Cetak: {{ $generatedDate }}</p>
        @if(!empty($filters['start_date']) || !empty($filters['end_date']))
        <p>
            Periode: 
            {{ $filters['start_date'] ?? 'Awal' }} s/d 
            {{ $filters['end_date'] ?? 'Sekarang' }}
        </p>
        @endif
    </div>

    <div class="summary">
        <table>
            <tr>
                <th width="150">Total Transaksi:</th>
                <td class="text-right"><strong>Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Sudah Dibayar:</th>
                <td class="text-right"><strong>Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Sisa Hutang:</th>
                <td class="text-right"><strong>Rp {{ number_format($totalRemaining, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Buku</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Dibayar</th>
                <th class="text-right">Sisa</th>
                <th>Status</th>
             </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                <td>{{ $transaction->book->code ?? '-' }}</td>
                <td>{{ $transaction->party->name ?? '-' }}</td>
                <td>{{ $transaction->description ?? '-' }}</td>
                <td class="text-right">{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($transaction->amount - $transaction->paid_amount, 0, ',', '.') }}</td>
                <td>{{ $transaction->payment_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }}</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
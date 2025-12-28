<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat EOQ</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; }
        .filter-info { font-size: 10px; text-align: left; margin-top: 15px; border: 1px solid #eee; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Riwayat Perhitungan EOQ</h1>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    @if($filters['start_date'] || $filters['end_date'] || $filters['raw_material_name'] !== 'Semua')
        <div class="filter-info">
            <strong>Filter yang diterapkan:</strong><br>
            @if($filters['start_date'] && $filters['end_date'])
                Periode: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }}<br>
            @endif
            Bahan Baku: {{ $filters['raw_material_name'] }}
        </div>
    @endif

    @if($calculations->isEmpty())
        <p>Tidak ada riwayat perhitungan yang ditemukan untuk filter yang dipilih.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan Baku</th>
                    <th>Permintaan (D)</th>
                    <th>Biaya Pesan (S)</th>
                    <th>Biaya Simpan (H)</th>
                    <th>Nilai EOQ</th>
                    <th>Frekuensi</th>
                    <th>Total Biaya (TC)</th>
                    <th>Reorder Point (ROP)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calculations as $calculation)
                    <tr>
                        <td>{{ $calculation->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $calculation->rawMaterial->name ?? 'N/A' }}</td>
                        <td>{{ number_format($calculation->annual_demand, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($calculation->ordering_cost, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($calculation->holding_cost, 0, ',', '.') }}</td>
                        <td>{{ number_format($calculation->eoq_value, 2, ',', '.') }} unit</td>
                        <td>{{ number_format($calculation->optimal_frequency, 2, ',', '.') }}x</td>
                        <td>Rp {{ number_format($calculation->total_cost, 0, ',', '.') }}</td>
                        <td>{{ number_format($calculation->rop_value, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak oleh Sistem Pendukung Keputusan Mitra10 pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
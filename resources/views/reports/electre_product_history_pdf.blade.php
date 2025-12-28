<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Perhitungan ELECTRE Produk</title>
    <style>
        body { font-family: sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .text-center { text-align: center; }
        h3 { text-align: center; }
        .filter-info { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h3>Laporan Riwayat Perhitungan Peringkat Produk (ELECTRE)</h3>

    <div class="filter-info">
        <strong>Filter Tanggal:</strong>
        {{ $filters['start_date'] ? \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') : 'N/A' }} -
        {{ $filters['end_date'] ? \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') : 'N/A' }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Perhitungan</th>
                <th>Hasil Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($calculations as $index => $calculation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $calculation->created_at->format('d F Y H:i:s') }}</td>
                    <td>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Produk</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ranking = $calculation->ranking;
                                    arsort($ranking);
                                @endphp
                                @php $rank = 1; @endphp
                                @foreach ($ranking as $productName => $score)
                                    <tr>
                                        <td>{{ $rank++ }}</td>
                                        <td>{{ $productName }}</td>
                                        <td>{{ $score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data ditemukan untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
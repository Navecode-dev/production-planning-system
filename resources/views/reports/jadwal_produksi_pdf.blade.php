<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Produksi</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            position: relative;
        }
        .header img {
            position: absolute;
            left: 0;
            top: 0;
            height: 50px; /* atur sesuai ukuran logo */
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            background-color: #f8f9fa;
            padding: 8px;
            margin: 0 0 10px 0;
            border-left: 4px solid #007bff;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .rank-1 { background-color: #d4edda; }
        .rank-2 { background-color: #fff3cd; }
        .rank-3 { background-color: #f8d7da; }
        .recommendation {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            margin-top: 20px;
        }
        .recommendation h4 {
            margin-top: 0;
            color: #0056b3;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        {{-- LOGO --}}
        <img src="{{ public_path('logo-splus.png') }}" alt="Logo" width="100">
        <h1>JADWAL PRODUKSI</h1>
        <p>Berdasarkan Ranking ELECTRE</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i:s') }}</p>
    </div>

    @if($latestProductCalculation || $latestStoreCalculation)
        <!-- Ranking Produk -->
        @if($latestProductCalculation)
        <div class="section">
            <h3>Prioritas Produk</h3>
            <p><strong>Perhitungan terakhir:</strong> {{ $latestProductCalculation->created_at->format('d F Y H:i:s') }}</p>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Prioritas</th>
                        <th width="65%">Nama Produk</th>
                        <th width="20%">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productRanking as $product)
                    <tr class="{{ $product['rank'] <= 3 ? 'rank-' . $product['rank'] : '' }}">
                        <td class="text-center"><strong>#{{ $product['rank'] }}</strong></td>
                        <td>{{ $product['name'] }}</td>
                        <td class="text-center">
                            @if($product['rank'] <= 3)
                                Tinggi
                            @elseif($product['rank'] <= 5)
                                Sedang
                            @else
                                Rendah
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Ranking Toko -->
        @if($latestStoreCalculation)
        <div class="section">
            <h3>Prioritas Toko</h3>
            <p><strong>Perhitungan terakhir:</strong> {{ $latestStoreCalculation->created_at->format('d F Y H:i:s') }}</p>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Prioritas</th>
                        <th width="65%">Nama Toko</th>
                        <th width="20%">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storeRanking as $store)
                    <tr class="{{ $store['rank'] <= 3 ? 'rank-' . $store['rank'] : '' }}">
                        <td class="text-center"><strong>#{{ $store['rank'] }}</strong></td>
                        <td>{{ $store['name'] }}</td>
                        <td class="text-center">
                            @if($store['rank'] <= 3)
                                Tinggi
                            @elseif($store['rank'] <= 5)
                                Sedang
                            @else
                                Rendah
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Rekomendasi -->
        @if($latestProductCalculation && $latestStoreCalculation && count($productRanking) > 0 && count($storeRanking) > 0)
        <div class="recommendation">
            <h4>Rekomendasi Jadwal Produksi</h4>
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 25%;"><strong>Produk Prioritas 1:</strong></td>
                    <td style="border: none;">{{ $productRanking[0]['name'] }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Toko Prioritas 1:</strong></td>
                    <td style="border: none;">{{ $storeRanking[0]['name'] }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Rekomendasi:</strong></td>
                    <td style="border: none;">
                        Prioritaskan produksi <strong>{{ $productRanking[0]['name'] }}</strong> untuk didistribusikan ke <strong>{{ $storeRanking[0]['name'] }}</strong>
                    </td>
                </tr>
            </table>
            
            <p style="margin-top: 15px;"><strong>Urutan Prioritas Produksi:</strong></p>
            <ol>
                @foreach(array_slice($productRanking, 0, 5) as $product)
                    <li>{{ $product['name'] }}</li>
                @endforeach
            </ol>
        </div>
        @endif
    @else
        <div style="text-align: center; margin-top: 50px;">
            <h4>Data Belum Tersedia</h4>
            <p>Silakan lakukan perhitungan ELECTRE untuk produk dan toko terlebih dahulu.</p>
        </div>
    @endif

    <div class="footer">
        Dicetak oleh Sistem Pendukung Keputusan Mitra10 pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>

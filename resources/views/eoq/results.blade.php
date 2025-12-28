@extends("layouts.admin")

@section("title", "Hasil EOQ")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Perhitungan EOQ untuk: <strong>{{ $rawMaterial->name }}</strong></h3>
                </div>
                <div class="card-body">
                    <p>Bahan Baku: <strong>{{ $rawMaterial->name }}</strong></p>
                    <p>Permintaan Tahunan (D): <strong>{{ number_format($demand, 0, ',', '.') }} unit</strong></p>
                    <p>Biaya Pemesanan per Pesanan (S): <strong>Rp {{ number_format($orderingCost, 2, ',', '.') }}</strong></p>
                    <p>Biaya Penyimpanan per Unit per Tahun (H): <strong>Rp {{ number_format($holdingCost, 2, ',', '.') }}</strong></p>
                    <hr>
                    <h4>Kuantitas Pemesanan Ekonomis (EOQ): <strong>{{ number_format($eoq, 2, ',', '.') }} unit</strong></h4>
                    <p class="text-muted"><em>Rumus: √((2 * D * S) / H)</em></p>
                    <p>Ini adalah kuantitas optimal untuk dipesan setiap kali untuk meminimalkan total biaya persediaan.</p>
                    <br>
                    <p>Frekuensi Pemesanan Optimal: <strong>{{ number_format($optimalFrequency, 2, ',', '.') }} kali per tahun</strong></p>
                    <p class="text-muted"><em>Rumus: D / EOQ</em></p>
                    <br>
                    <p>Total Biaya Persediaan (TC): <strong>Rp {{ number_format($totalCost, 2, ',', '.') }} per tahun</strong></p>
                    <p class="text-muted"><em>Rumus (TC) : (D / EOQ) * S + (EOQ / 2) * H</em></p>
                    <br>

                    <hr>
                    <h4>Hasil Perhitungan Reorder Point (ROP)</h4>
                    @if(isset($rop))
                        <p>Permintaan Harian Rata-rata: <strong>{{ number_format($dailyDemand, 0, ',', '.') }} unit/hari</strong></p>
                        <p class="text-muted"><em>Rumus: Permintaan Tahunan / Hari Kerja Setahun</em></p>

                        <p>Safety Stock: <strong>{{ number_format($safetyStock, 0, ',', '.') }} unit</strong></p>
                        <p class="text-muted"><em>Rumus: (Permintaan Harian Maksimum - Permintaan Harian Rata-rata) * Lead Time</em></p>

                        <p>Reorder Point (ROP): <strong>{{ number_format($rop, 0, ',', '.') }} unit</strong></p>
                        <p class="text-muted"><em>Rumus: (Permintaan Harian Rata-rata * Lead Time) + Safety Stock</em></p>
                        <p>Pemesanan harus dilakukan kembali ketika tingkat persediaan mencapai titik ini.</p>
                        <br>
                    @endif
                    <a href="{{ route('eoq.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Bahan Baku</a>
                </div>
            </div>
        </div>
    </div>
@endsection
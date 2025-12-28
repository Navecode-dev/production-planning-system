@extends('layouts.admin')

@section('title', 'Jadwal Produksi')

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card-header { 
            background-color: #28a745 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body { background: white !important; }
    }
    
    .production-item {
        border-left: 4px solid #28a745;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    
    .product-badge {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        border-radius: 4px;
        width: fit-content;
    }
    
    /* Modal Styles */
    .modal-xl { max-width: 95%; }
    
    .matrix-table {
        font-size: 0.85em;
        width: 100%;
    }
    
    .matrix-table th, .matrix-table td {
        text-align: center;
        vertical-align: middle;
        padding: 8px 4px;
        border: 1px solid #dee2e6;
    }
    
    .matrix-table .header-cell {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    .matrix-table .item-name {
        text-align: left;
        font-weight: bold;
        background-color: #f8f9fa;
    }
    
    .formula-section {
        margin: 20px 0;
        padding: 15px;
        background-color: #f9f9f9;
        border: 1px solid #e3e3e3;
        border-radius: 5px;
    }
    
    .formula-section h5 {
        color: #333;
        margin-top: 0;
    }
    
    .formula-text {
        font-size: 0.95em;
        color: #555;
        margin: 10px 0;
    }
    
    .formula-code {
        display: block;
        text-align: center;
        margin: 15px 0;
        font-size: 1.1em;
        font-family: monospace;
        background-color: #eee;
        padding: 10px;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-white mb-0">
                        <i class="fas fa-calendar-check"></i> {{ $schedule->schedule_name }}
                    </h3>
                    <div class="btn-group no-print">
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#electreModal">
                            <i class="fas fa-calculator"></i> Lihat Perhitungan
                        </button>
                        <button onclick="window.print()" class="btn btn-light btn-sm">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <a href="{{ route('production-schedule.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show no-print">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif

                <h4 class="mb-4"><i class="fas fa-tasks"></i> Urutan Produksi Berdasarkan Prioritas</h4>

                @foreach($schedule->scheduleStores as $store)
                    <div class="production-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1">
                                    <span class="badge badge-success">Prioritas #{{ $store->rank_position }}</span>
                                    <strong>{{ $store->store->name }}</strong>
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $store->store->address }} | 
                                    <i class="fas fa-clock"></i> Deadline: {{ $store->deadline_days }} hari | 
                                    <i class="fas fa-box"></i> Total: {{ number_format($store->total_qty, 0) }} pcs
                                </small>
                            </div>
                            <button class="btn btn-sm btn-outline-info no-print" data-toggle="modal" data-target="#detailModal{{ $store->id }}">
                                <i class="fas fa-info-circle"></i> Detail
                            </button>
                        </div>

                        <div class="mt-3">
                            <strong>Produk yang Diproduksi (Urutan ELECTRE Produk):</strong><br>
                            @php
                                $sortedProducts = $store->products->sortBy(function($item) use ($productRankings) {
                                    return $productRankings[$item->product->name]['position'] ?? 999;
                                });
                            @endphp
                            @foreach($sortedProducts as $index =>$product)
                                <div class="product-badge">
                                <strong>#{{ $loop->iteration }}</strong>
                                    {{ $product->product->name }} 
                                    <span class="text-primary">
                                    ({{ number_format($product->quantity, 0) }} {{ $product->product->unit }})
                                    </span> 
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Cetak Jadwal
                    </button>
                    <a href="{{ route('production-schedule.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Toko -->
@foreach($schedule->scheduleStores as $store)
<div class="modal fade" id="detailModal{{ $store->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">
                    <i class="fas fa-store"></i> Detail: {{ $store->store->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Informasi Toko:</strong>
                        <ul class="list-unstyled mt-2">
                            <li><i class="fas fa-store"></i> {{ $store->store->name }}</li>
                            <li><i class="fas fa-map-marker-alt"></i> {{ $store->store->address }}</li>
                            <li><i class="fas fa-phone"></i> {{ $store->store->contact ?? '-' }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <strong>Ringkasan Pesanan:</strong>
                        <ul class="list-unstyled mt-2">
                            <li><i class="fas fa-trophy"></i> Ranking: #{{ $store->rank_position }}</li>
                            <li><i class="fas fa-chart-line"></i> Score: {{ number_format($store->rank_score, 4) }}</li>
                            <li><i class="fas fa-calendar"></i> Deadline: {{ $store->deadline_days }} hari</li>
                            <li><i class="fas fa-boxes"></i> Total: {{ number_format($store->total_qty, 0) }} pcs</li>
                        </ul>
                    </div>
                </div>

                <h6><i class="fas fa-list"></i> Detail Produk:</h6>
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Produk</th>
                            <th width="20%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($store->products as $product)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $product->product->name }}</strong>
                                    @if(isset($productRankings[$product->product->name]))
                                        <br><span class="badge badge-success">Prioritas #{{ $productRankings[$product->product->name]['position'] }}</span>
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($product->quantity, 0) }} {{ $product->product->unit }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Perhitungan ELECTRE -->
<div class="modal fade" id="electreModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-calculator"></i> Perhitungan ELECTRE - Ranking Toko
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                
                @php
                    // Ambil data untuk perhitungan
                    $stores = $schedule->scheduleStores;
                    $criteriaList = $stores->first()->scores ?? collect();
                    $storeCount = $stores->count();
                    $criteriaCount = $criteriaList->count();
                    
                    // Hitung semua matriks
                    $matrix = [];
                    $weights = [];
                    $types = [];
                    
                    foreach ($stores as $idx => $store) {
                        $matrix[$idx] = [];
                        foreach ($store->scores as $score) {
                            $matrix[$idx][] = (float)$score->normalized_value;
                            if ($idx === 0) {
                                $weights[] = (float)$score->weight;
                                $types[] = $score->criteria_type;
                            }
                        }
                    }
                    
                    // 1. Matriks Ternormalisasi
                    $normalized = [];
                    for ($j = 0; $j < $criteriaCount; $j++) {
                        $sum = 0;
                        for ($i = 0; $i < $storeCount; $i++) {
                            $sum += pow($matrix[$i][$j], 2);
                        }
                        $sqrt = sqrt($sum);
                        for ($i = 0; $i < $storeCount; $i++) {
                            $normalized[$i][$j] = $sqrt > 0 ? $matrix[$i][$j] / $sqrt : 0;
                        }
                    }
                    
                    // 2. Matriks Terbobot
                    $weighted = [];
                    for ($i = 0; $i < $storeCount; $i++) {
                        for ($j = 0; $j < $criteriaCount; $j++) {
                            $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
                        }
                    }
                    
                    // 3. Concordance & Discordance Matrix
                    $concordance = [];
                    $discordance = [];
                    
                    for ($i = 0; $i < $storeCount; $i++) {
                        for ($k = 0; $k < $storeCount; $k++) {
                            if ($i !== $k) {
                                $concordance[$i][$k] = 0;
                                $maxDiff = 0;
                                
                                for ($j = 0; $j < $criteriaCount; $j++) {
                                    // Concordance
                                    if (($types[$j] === 'benefit' && $weighted[$i][$j] >= $weighted[$k][$j]) ||
                                        ($types[$j] === 'cost' && $weighted[$i][$j] <= $weighted[$k][$j])) {
                                        $concordance[$i][$k] += $weights[$j];
                                    }
                                    
                                    // Discordance
                                    $diff = abs($weighted[$i][$j] - $weighted[$k][$j]);
                                    if ($diff > $maxDiff) {
                                        $maxDiff = $diff;
                                    }
                                }
                                
                                // Normalisasi Discordance
                                $maxAll = 0;
                                for ($j = 0; $j < $criteriaCount; $j++) {
                                    $diff = abs($weighted[$i][$j] - $weighted[$k][$j]);
                                    if ($diff > $maxAll) {
                                        $maxAll = $diff;
                                    }
                                }
                                $discordance[$i][$k] = $maxAll > 0 ? $maxDiff / $maxAll : 0;
                            } else {
                                $concordance[$i][$k] = 0;
                                $discordance[$i][$k] = 0;
                            }
                        }
                    }
                    
                    // 4. Threshold
                    $concordanceSum = 0;
                    $discordanceSum = 0;
                    $count = 0;
                    
                    for ($i = 0; $i < $storeCount; $i++) {
                        for ($k = 0; $k < $storeCount; $k++) {
                            if ($i !== $k) {
                                $concordanceSum += $concordance[$i][$k];
                                $discordanceSum += $discordance[$i][$k];
                                $count++;
                            }
                        }
                    }
                    
                    $concordanceThreshold = $count > 0 ? $concordanceSum / $count : 0;
                    $discordanceThreshold = $count > 0 ? $discordanceSum / $count : 0;
                    
                    // 5. Dominance Matrix
                    $concordanceDominance = [];
                    $discordanceDominance = [];
                    $aggregateDominance = [];
                    
                    for ($i = 0; $i < $storeCount; $i++) {
                        for ($k = 0; $k < $storeCount; $k++) {
                            $concordanceDominance[$i][$k] = ($concordance[$i][$k] >= $concordanceThreshold) ? 1 : 0;
                            $discordanceDominance[$i][$k] = ($discordance[$i][$k] <= $discordanceThreshold) ? 1 : 0;
                            $aggregateDominance[$i][$k] = $concordanceDominance[$i][$k] * $discordanceDominance[$i][$k];
                        }
                    }
                @endphp

                <!-- Langkah 0 -->
                <div class="formula-section">
                    <h5>Langkah 0: Matriks Keputusan (Nilai Asli)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell">Toko / Kriteria</th>
                                    @foreach($criteriaList as $c)
                                        <th class="header-cell">{{ $c->criteria_name }}<br><small>({{ ucfirst($c->criteria_type) }})</small></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $idx => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($matrix[$idx] as $val)
                                            <td>{{ number_format($val, 2) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Matriks ini berisi nilai asli (skala 1-5) setiap toko terhadap setiap kriteria.</p>
                </div>

                <!-- Langkah 1 -->
                <div class="formula-section">
                    <h5>Langkah 1: Matriks Ternormalisasi</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell">Toko / Kriteria</th>
                                    @foreach($criteriaList as $c)
                                        <th class="header-cell">{{ $c->criteria_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $idx => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($normalized[$idx] as $val)
                                            <td>{{ number_format($val, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Setiap elemen dinormalisasi menggunakan rumus:</p>
                    <code class="formula-code">r<sub>ij</sub> = x<sub>ij</sub> / √(Σ x<sub>kj</sub>²)</code>
                </div>

                <!-- Langkah 2 -->
                <div class="formula-section">
                    <h5>Langkah 2: Matriks Ternormalisasi Terbobot</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell">Toko / Kriteria</th>
                                    @foreach($criteriaList as $c)
                                        <th class="header-cell">{{ $c->criteria_name }}<br><small>(w={{ $c->weight }})</small></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $idx => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($weighted[$idx] as $val)
                                            <td>{{ number_format($val, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Setiap elemen dikalikan dengan bobot kriteria:</p>
                    <code class="formula-code">v<sub>ij</sub> = w<sub>j</sub> × r<sub>ij</sub></code>
                </div>

                <!-- Langkah 3 & 4 -->
                <div class="formula-section">
                    <h5>Langkah 3: Matriks Concordance (C)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell"></th>
                                    @foreach($stores as $s)
                                        <th class="header-cell">{{ $s->store->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($stores as $k => $s)
                                            @if($i == $k)
                                                <td style="background:#e9ecef">-</td>
                                            @else
                                                <td>{{ number_format($concordance[$i][$k], 4) }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Mengukur seberapa kuat toko i mendominasi toko k berdasarkan kriteria yang mendukung:</p>
                    <code class="formula-code">c<sub>ik</sub> = Σ(w<sub>j</sub>) untuk kriteria dimana toko i ≥ toko k</code>
                </div>

                <div class="formula-section">
                    <h5>Langkah 4: Matriks Discordance (D)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell"></th>
                                    @foreach($stores as $s)
                                        <th class="header-cell">{{ $s->store->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($stores as $k => $s)
                                            @if($i == $k)
                                                <td style="background:#e9ecef">-</td>
                                            @else
                                                <td>{{ number_format($discordance[$i][$k], 4) }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Mengukur perbedaan maksimum pada kriteria dimana toko i lebih buruk dari toko k:</p>
                    <code class="formula-code">d<sub>ik</sub> = max|v<sub>ij</sub> - v<sub>kj</sub>| / max|v<sub>ij</sub> - v<sub>kj</sub>| (semua kriteria)</code>
                </div>

                <!-- Langkah 5 -->
                <div class="formula-section">
                    <h5>Langkah 5a: Matriks Dominasi Concordance (F)</h5>
                    <p class="formula-text">Threshold: <strong>{{ number_format($concordanceThreshold, 4) }}</strong></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell"></th>
                                    @foreach($stores as $s)
                                        <th class="header-cell">{{ $s->store->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($stores as $k => $s)
                                            @if($i == $k)
                                                <td style="background:#e9ecef">-</td>
                                            @else
                                                <td>{{ $concordanceDominance[$i][$k] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Dominasi berdasarkan threshold concordance:</p>
                    <code class="formula-code">f<sub>ik</sub> = 1 jika c<sub>ik</sub> ≥ c*, else 0</code>
                </div>

                <div class="formula-section">
                    <h5>Langkah 5b: Matriks Dominasi Discordance (G)</h5>
                    <p class="formula-text">Threshold: <strong>{{ number_format($discordanceThreshold, 4) }}</strong></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell"></th>
                                    @foreach($stores as $s)
                                        <th class="header-cell">{{ $s->store->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($stores as $k => $s)
                                            @if($i == $k)
                                                <td style="background:#e9ecef">-</td>
                                            @else
                                                <td>{{ $discordanceDominance[$i][$k] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">Dominasi berdasarkan threshold discordance:</p>
                    <code class="formula-code">g<sub>ik</sub> = 1 jika d<sub>ik</sub> ≤ d*, else 0</code>
                </div>

                               <!-- Langkah 6 -->
                               <div class="formula-section">
                    <h5>Langkah 6: Matriks Dominasi Agregat (E)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm matrix-table">
                            <thead>
                                <tr>
                                    <th class="header-cell"></th>
                                    @foreach($stores as $s)
                                        <th class="header-cell">{{ $s->store->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td class="item-name">{{ $store->store->name }}</td>
                                        @foreach($stores as $k => $s)
                                            @if($i == $k)
                                                <td style="background:#e9ecef">-</td>
                                            @else
                                                <td>{{ $aggregateDominance[$i][$k] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="formula-section">
                    <p class="formula-text">
                        Matriks ini menunjukkan apakah toko i secara keseluruhan mendominasi toko k. Dominasi terjadi jika kriteria concordance dan discordance terpenuhi.
                    </p>
                    <code class="formula-code">e<sub>ik</sub> = f<sub>ik</sub> × g<sub>ik</sub></code>
                </div>

                <div class="mt-4">
                    <h4>Langkah 7: Peringkat Toko Akhir</h4>
                    <p class="formula-text">
                        Peringkat dihitung berdasarkan jumlah 'kemenangan' (dominasi) dikurangi jumlah 'kekalahan' dari matriks dominansi agregat.
                    </p>
                    <code class="formula-code">Skor = (Jumlah Kemenangan) - (Jumlah Kekalahan)</code>
                    
                    @php
                        $ranking = [];
                        foreach($stores as $i => $store) {
                            $wins = 0;
                            $losses = 0;
                            for ($k = 0; $k < $storeCount; $k++) {
                                if ($i !== $k) {
                                    $wins += $aggregateDominance[$i][$k];
                                    $losses += $aggregateDominance[$k][$i];
                                }
                            }
                            $ranking[] = [
                                'store' => $store,
                                'wins' => $wins,
                                'losses' => $losses,
                                'score' => $wins - $losses
                            ];
                        }
                        
                        // Sort by score descending
                        usort($ranking, function($a, $b) {
                            return $b['score'] <=> $a['score'];
                        });
                    @endphp
                    
                    @if(empty($ranking))
                    <p>Tidak ada peringkat tersedia.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Peringkat</th>
                                    <th class="text-center">Toko</th>
                                    <th class="text-center">Skor (Menang - Kalah)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranking as $index => $result)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center"><strong>{{ $result['store']->store->name }}</strong></td>
                                        <td class="text-center">{{ $result['score'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
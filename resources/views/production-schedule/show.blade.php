@extends('layouts.admin')

@section('title', 'Detail Jadwal Produksi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $schedule->schedule_name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('production-schedule.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="30%">Nama Jadwal</th>
                                <td>{{ $schedule->schedule_name }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $schedule->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($schedule->status === 'draft')
                                        <span class="badge badge-warning">Draft</span>
                                    @elseif($schedule->status === 'calculated')
                                        <span class="badge badge-success">Selesai Dihitung</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="30%">Jumlah Toko</th>
                                <td>{{ $schedule->scheduleStores->count() }} Toko</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @if($schedule->calculated_at)
                            <tr>
                                <th>Tanggal Dihitung</th>
                                <td>{{ $schedule->calculated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($schedule->status === 'draft' && $schedule->scheduleStores->count() > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Jadwal ini belum dihitung. 
                    <form action="{{ route('production-schedule.calculate', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Yakin ingin menghitung ranking toko?')">
                            <i class="fas fa-calculator"></i> Hitung ELECTRE Sekarang
                        </button>
                    </form>
                </div>
                @endif

                @if($schedule->scheduleStores->count() > 0)
                <h5 class="mt-4">Detail Toko dan Produk</h5>

                @foreach($schedule->scheduleStores as $index => $scheduleStore)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>{{ $scheduleStore->store->name }}</strong>
                        @if($schedule->status === 'calculated' && $scheduleStore->rank_position)
                            <span class="badge badge-warning float-right">
                                @if($scheduleStore->rank_position == 1) 🥇
                                @elseif($scheduleStore->rank_position == 2) 🥈
                                @elseif($scheduleStore->rank_position == 3) 🥉
                                @endif
                                Ranking #{{ $scheduleStore->rank_position }}
                            </span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fas fa-store"></i> Informasi Toko:</strong>
                                <ul class="list-unstyled mt-2">
                                    <li><strong>Alamat:</strong> {{ $scheduleStore->store->address }}</li>
                                    <li><strong>Kontak:</strong> {{ $scheduleStore->store->contact }}</li>
                                    <li><strong>PIC:</strong> {{ $scheduleStore->store->person_in_charge }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-shopping-cart"></i> Data Pesanan:</strong>
                                <ul class="list-unstyled mt-2">
                                    <li><strong>Deadline:</strong> {{ $scheduleStore->deadline_days }} hari</li>
                                    <li><strong>Total Qty:</strong> {{ number_format($scheduleStore->total_qty, 0) }} unit</li>
                                    <li><strong>Variasi Produk:</strong> {{ $scheduleStore->product_variety }} jenis</li>
                                </ul>
                            </div>
                        </div>

                        <strong><i class="fas fa-box"></i> Produk yang Dipesan:</strong>
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Produk</th>
                                    <th width="20%">Jumlah</th
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scheduleStore->products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ number_format($product->quantity, 0) }} {{ $product->product->unit }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada produk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <strong><i class="fas fa-chart-bar"></i> Nilai Kriteria:</strong>
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Sumber</th>
                                    <th>Tipe</th>
                                    <th>Nilai Asli</th>
                                    <th>Nilai Konversi (1-5)</th>
                                    <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scheduleStore->scores as $score)
                                <tr>
                                    <td>{{ $score->criteria_name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $score->source === 'master' ? 'primary' : 'success' }}">
                                            {{ $score->source === 'master' ? 'Master' : 'Pesanan' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $score->criteria_type === 'benefit' ? 'success' : 'warning' }}">
                                            {{ ucfirst($score->criteria_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $criteriaLower = strtolower($score->criteria_name);
                                        @endphp
                                        @if(str_contains($criteriaLower, 'qty') || str_contains($criteriaLower, 'total'))
                                            {{ number_format($score->raw_value, 0) }} unit
                                        @elseif(str_contains($criteriaLower, 'variasi') || str_contains($criteriaLower, 'produk'))
                                            {{ $score->raw_value }} jenis
                                        @elseif(str_contains($criteriaLower, 'deadline') || str_contains($criteriaLower, 'kiriman'))
                                            {{ $score->raw_value }} hari
                                        @elseif(str_contains($criteriaLower, 'jarak'))
                                            {{ $score->raw_value }} km
                                        @elseif(str_contains($criteriaLower, 'frekuensi'))
                                            {{ $score->raw_value }} kali
                                        @else
                                            {{ $score->raw_value }}
                                        @endif
                                    </td>
                                    <td><strong>{{ $score->normalized_value }}</strong></td>
                                    <td>{{ $score->weight * 100 }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($schedule->status === 'calculated' && $scheduleStore->rank_score)
                        <div class="alert alert-success mt-3">
                            <strong><i class="fas fa-trophy"></i> Hasil Perhitungan ELECTRE:</strong><br>
                            Score: <strong>{{ number_format($scheduleStore->rank_score, 4) }}</strong><br>
                            Ranking: <strong>#{{ $scheduleStore->rank_position }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="mt-4">
                    @if($schedule->status === 'calculated')
                        <a href="{{ route('production-schedule.result', $schedule->id) }}" class="btn btn-success">
                            <i class="fas fa-trophy"></i> Lihat Hasil Lengkap & Ranking
                        </a>
                    @elseif($schedule->scheduleStores->count() > 1)
                        <form action="{{ route('production-schedule.calculate', $schedule->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin menghitung ranking toko?')">
                                <i class="fas fa-calculator"></i> Hitung ELECTRE
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Minimal 2 toko diperlukan untuk perhitungan ranking.
                        </div>
                    @endif
                    
                    <a href="{{ route('production-schedule.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Tidak ada data toko dalam jadwal ini.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
@endsection
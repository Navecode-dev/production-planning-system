@extends('layouts.admin')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Jadwal Produksi Berdasarkan Ranking ELECTRE</h3>
                    <a href="{{ route('reports.jadwal-produksi.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
                
                <div class="card-body">
                    @if($latestProductCalculation || $latestStoreCalculation)
                        <div class="row">
                            <!-- Ranking Produk -->
                            <div class="col-md-6">
                                <h5>Prioritas Produk</h5>
                                @if($latestProductCalculation)
                                    <small class="text-muted">
                                        Perhitungan terakhir: {{ $latestProductCalculation->created_at->format('d F Y H:i') }}
                                    </small>
                                    
                                    <div class="table-responsive mt-3">
                                        <table class="table table-striped table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Prioritas</th>
                                                    <th>Produk</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productRanking as $product)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-{{ $product['rank'] <= 3 ? 'success' : ($product['rank'] <= 5 ? 'warning' : 'secondary') }}">
                                                                #{{ $product['rank'] }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $product['name'] }}</td>
                                                        <td>
                                                            @if($product['rank'] <= 3)
                                                                <span class="badge badge-success">Prioritas Tinggi</span>
                                                            @elseif($product['rank'] <= 5)
                                                                <span class="badge badge-warning">Prioritas Sedang</span>
                                                            @else
                                                                <span class="badge badge-secondary">Prioritas Rendah</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        Belum ada perhitungan ranking produk. Silakan lakukan perhitungan ELECTRE untuk produk terlebih dahulu.
                                    </div>
                                @endif
                            </div>

                            <!-- Ranking Store -->
                            <div class="col-md-6">
                                <h5>Prioritas Toko</h5>
                                @if($latestStoreCalculation)
                                    <small class="text-muted">
                                        Perhitungan terakhir: {{ $latestStoreCalculation->created_at->format('d F Y H:i') }}
                                    </small>
                                    
                                    <div class="table-responsive mt-3">
                                        <table class="table table-striped table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Prioritas</th>
                                                    <th>Toko</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($storeRanking as $store)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-{{ $store['rank'] <= 3 ? 'success' : ($store['rank'] <= 5 ? 'warning' : 'secondary') }}">
                                                                #{{ $store['rank'] }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $store['name'] }}</td>
                                                        <td>
                                                            @if($store['rank'] <= 3)
                                                                <span class="badge badge-success">Prioritas Tinggi</span>
                                                            @elseif($store['rank'] <= 5)
                                                                <span class="badge badge-warning">Prioritas Sedang</span>
                                                            @else
                                                                <span class="badge badge-secondary">Prioritas Rendah</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        Belum ada perhitungan ranking toko. Silakan lakukan perhitungan ELECTRE untuk toko terlebih dahulu.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Rekomendasi Jadwal -->
                        @if($latestProductCalculation && $latestStoreCalculation)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5>Rekomendasi Jadwal Produksi</h5>
                                    <div class="alert alert-primary">
                                        <h6><i class="fas fa-lightbulb"></i> Berdasarkan Ranking ELECTRE:</h6>
                                        <p>
                                            <strong>Produk Prioritas Utama:</strong> 
                                            @if(count($productRanking) > 0)
                                                {{ $productRanking[0]['name'] }}
                                            @endif
                                        </p>
                                        <p>
                                            <strong>Toko Prioritas Utama:</strong> 
                                            @if(count($storeRanking) > 0)
                                                {{ $storeRanking[0]['name'] }}
                                            @endif
                                        </p>
                                        <p class="mb-0">
                                            <em>Disarankan untuk memprioritaskan produksi produk dengan ranking tertinggi untuk distribusi ke toko dengan ranking tertinggi.</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Data Belum Tersedia</h5>
                            <p>Untuk membuat jadwal produksi, Anda perlu melakukan perhitungan ELECTRE terlebih dahulu:</p>
                            <ul>
                                <li>Lakukan perhitungan ranking produk menggunakan metode ELECTRE</li>
                                <li>Lakukan perhitungan ranking toko menggunakan metode ELECTRE</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

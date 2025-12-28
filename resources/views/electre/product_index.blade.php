@extends('layouts.admin')

@section('title', 'ELECTRE - Peringkat Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Metode ELECTRE untuk Peringkat Produk</h3>
                <div class="card-tools">
                    <a href="{{ route('electre.product.history') }}" class="btn btn-info btn-sm">Lihat Riwayat Perhitungan</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($criteria->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada kriteria ditemukan untuk kategori 'produk'. Silakan <a href="{{ route('criteria.create') }}">tambahkan kriteria</a> terlebih dahulu untuk melakukan perhitungan.
                    </div>
                @elseif($products->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada produk ditemukan. Silakan <a href="{{ route('products.create') }}">tambahkan produk</a> terlebih dahulu.
                    </div>
                @else
                    <h4>Matriks Keputusan (Skor)</h4>
                    <p>Tabel ini menunjukkan skor untuk setiap produk berdasarkan kriteria yang ditentukan. Pastikan semua skor telah dimasukkan sebelum menghitung.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    @foreach ($criteria as $criterion)
                                        <th>{{ $criterion->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            {{ $product->name }}
                                            @if($criteria->isNotEmpty() && $product->scores->count() < $criteria->count())
                                                <span class="badge badge-danger ml-2" title="Skor tidak lengkap untuk produk ini.">!</span>
                                            @endif
                                        </td>
                                        @foreach ($criteria as $criterion)
                                            <td>{{ $product->scores->where('criteria_id', $criterion->id)->first()->value ?? 0 }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('electre.product.calculate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Hitung Peringkat Produk</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'ELECTRE - Peringkat Toko')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Metode ELECTRE untuk Peringkat Toko</h3>
                <div class="card-tools">
                    <a href="{{ route('electre.store.history') }}" class="btn btn-info btn-sm">Lihat Riwayat Perhitungan</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($criteria->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada kriteria ditemukan untuk kategori 'toko'. Silakan <a href="{{ route('criteria.create') }}">tambahkan kriteria</a> terlebih dahulu untuk melakukan perhitungan.
                    </div>
                @elseif($stores->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada toko ditemukan. Silakan <a href="{{ route('stores.create') }}">tambahkan toko</a> terlebih dahulu.
                    </div>
                @else
                    <h4>Matriks Keputusan (Skor)</h4>
                    <p>Tabel ini menunjukkan skor untuk setiap toko berdasarkan kriteria yang ditentukan. Pastikan semua skor telah dimasukkan sebelum menghitung.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Toko</th>
                                    @foreach ($criteria as $criterion)
                                        <th>{{ $criterion->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                    <tr>
                                        <td>
                                            {{ $store->name }}
                                            @if($criteria->isNotEmpty() && $store->scores->count() < $criteria->count())
                                                <span class="badge badge-danger ml-2" title="Skor tidak lengkap untuk toko ini.">!</span>
                                            @endif
                                        </td>
                                        @foreach ($criteria as $criterion)
                                            <td>{{ $store->scores->where('criteria_id', $criterion->id)->first()->value ?? 0 }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('electre.store.calculate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Hitung Peringkat Toko</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
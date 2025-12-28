@extends('layouts.admin')

@section('title', 'EOQ - Pilih Bahan Baku')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Bahan Baku untuk Menghitung EOQ</h3>
                </div>
                <div class="card-body">
                    @if($rawMaterials->isEmpty())
                        <div class="alert alert-warning">
                            Bahan baku tidak ditemukan. Silakan <a href="{{ route('raw-materials.create') }}">tambah bahan baku</a> terlebih dahulu.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Bahan Baku</th>
                                        <th>Biaya Penyimpanan (Holding Cost)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rawMaterials as $material)
                                        <tr>
                                            <td>{{ $material->name }}</td>
                                            <td>Rp {{ number_format($material->storage_cost, 2, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('eoq.calculator', $material->id) }}" class="btn btn-primary">Hitung EOQ</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
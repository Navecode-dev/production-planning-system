@extends('layouts.admin')

@section('title', 'Riwayat Perhitungan EOQ')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Riwayat</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('eoq.history') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Tanggal Selesai</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="raw_material_id">Bahan Baku</label>
                                <select name="raw_material_id" id="raw_material_id" class="form-control">
                                    <option value="">Semua Bahan Baku</option>
                                    @foreach($rawMaterials as $material)
                                        <option value="{{ $material->id }}" {{ (isset($rawMaterialId) && $rawMaterialId == $material->id) ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('eoq.history') }}" class="btn btn-secondary">Reset</a>
                                <a href="{{ route('reports.eoq.history.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hasil Riwayat Perhitungan EOQ</h3>
                <div class="card-tools">
                    <a href="{{ route('eoq.index') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-calculator"></i> Kembali ke Kalkulator
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($calculations->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada riwayat perhitungan yang ditemukan untuk filter yang dipilih.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                @foreach ($calculations as $calculation)
                                    <tr>
                                        <td>{{ $calculation->created_at->format('d M Y H:i') }}</td>
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
                    </div>
                    <div class="mt-3">
                        {{ $calculations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Kalkulator EOQ untuk ' . $rawMaterial->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kalkulator EOQ untuk: <strong>{{ $rawMaterial->name }}</strong></h3>
                <div class="card-tools">
                    <a href="{{ route('eoq.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Bahan Baku</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('eoq.calculate', $rawMaterial->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="demand">Permintaan Tahunan (D)</label>
                        <input type="number" name="demand" id="demand" class="form-control @error('demand') is-invalid @enderror" value="{{ old('demand') }}" step="any" required>
                        @error('demand')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Biaya Pemesanan per Pesanan (S)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" value="{{ number_format($totalSetupCost, 2, ',', '.') }}" readonly>
                        </div>
                        <small class="form-text text-muted">Nilai ini dihitung secara otomatis dari <a href="{{ route('setting-costs.index') }}">Pengaturan Biaya</a>.</small>
                    </div>
                    <div class="form-group">
                        <label>Biaya Penyimpanan per Unit per Tahun (H)</label>
                         <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" value="{{ number_format($holdingCost, 2, ',', '.') }}" readonly>
                        </div>
                        <small class="form-text text-muted">Nilai ini diambil dari biaya simpan bahan baku.</small>
                    </div>

                    <hr>
                    <h4 class="mt-4">Input untuk Reorder Point (ROP)</h4>

                    <div class="form-group">
                        <label for="lead_time">Lead Time (dalam hari)</label>
                        <input type="number" name="lead_time" id="lead_time" class="form-control @error('lead_time') is-invalid @enderror" value="{{ old('lead_time') }}" step="any" required>
                        @error('lead_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="working_days">Hari Kerja Setahun</label>
                        <input type="number" name="working_days" id="working_days" class="form-control @error('working_days') is-invalid @enderror" value="{{ old('working_days') }}" step="any" required>
                        @error('working_days')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="max_daily_demand">Permintaan Harian Maksimum (unit)</label>
                        <input type="number" name="max_daily_demand" id="max_daily_demand" class="form-control @error('max_daily_demand') is-invalid @enderror" value="{{ old('max_daily_demand') }}" step="any" required>
                        @error('max_daily_demand')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Hitung EOQ & ROP</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Tambah Bahan Baku Baru')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Bahan Baku Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('raw-materials.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Bahan Baku</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="price_per_pallet">Harga / Palet (Rp)</label>
                            <input type="number" name="price_per_pallet" id="price_per_pallet" class="form-control @error('price_per_pallet') is-invalid @enderror" value="{{ old('price_per_pallet') }}" step="0.01" required>
                            @error('price_per_pallet')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="storage_cost">Biaya Simpan (Rp)</label>
                            <input type="number" name="storage_cost" id="storage_cost" class="form-control @error('storage_cost') is-invalid @enderror" value="{{ old('storage_cost') }}" step="0.01" required>
                            @error('storage_cost')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="price_per_sheet">Harga / Lembar (Rp)</label>
                            <input type="number" name="price_per_sheet" id="price_per_sheet" class="form-control @error('price_per_sheet') is-invalid @enderror" value="{{ old('price_per_sheet') }}" step="0.01" required>
                            @error('price_per_sheet')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
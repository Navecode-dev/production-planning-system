@extends('layouts.admin')

@section('title', 'Tambah Toko Baru')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Toko Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('stores.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Toko</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Toko</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="sales_area">Area Penjualan</label>
                            <input type="text" name="sales_area" id="sales_area" class="form-control @error('sales_area') is-invalid @enderror" value="{{ old('sales_area') }}" required>
                            @error('sales_area')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="contact">Kontak</label>
                            <input type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" required>
                            @error('contact')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="person_in_charge">Penanggung Jawab</label>
                            <input type="text" name="person_in_charge" id="person_in_charge" class="form-control @error('person_in_charge') is-invalid @enderror" value="{{ old('person_in_charge') }}" required>
                            @error('person_in_charge')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('stores.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
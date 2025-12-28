@extends('layouts.admin')

@section('title', 'Detail Toko')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Toko: {{ $store->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-warning btn-sm">Edit Toko</a>
                        <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Toko:</label>
                        <p>{{ $store->name }}</p>
                    </div>
                    <div class="form-group">
                        <label>Alamat:</label>
                        <p>{{ $store->address }}</p>
                    </div>
                    <div class="form-group">
                        <label>Area Penjualan:</label>
                        <p>{{ $store->sales_area }}</p>
                    </div>
                    <div class="form-group">
                        <label>Kontak:</label>
                        <p>{{ $store->contact }}</p>
                    </div>
                    <div class="form-group">
                        <label>Penanggung Jawab:</label>
                        <p>{{ $store->person_in_charge }}</p>
                    </div>
                    <div class="form-group">
                        <label>Dibuat Pada:</label>
                        <p>{{ $store->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="form-group">
                        <label>Diperbarui Pada:</label>
                        <p>{{ $store->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
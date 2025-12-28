@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Produk: {{ $product->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit Produk</a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama:</label>
                        <p>{{ $product->name }}</p>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi:</label>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="form-group">
                        <label>Harga:</label>
                        <p>{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="form-group">
                        <label>Kategori:</label>
                        <p>{{ $product->category }}</p>
                    </div>
                    <div class="form-group">
                        <label>Unit:</label>
                        <p>{{ $product->unit }}</p>
                    </div>
                    <div class="form-group">
                        <label>Merek:</label>
                        <p>{{ $product->brand }}</p>
                    </div>
                    <div class="form-group">
                        <label>Barcode:</label>
                        <p>{{ $product->barcode }}</p>
                    </div>
                    <div class="form-group">
                        <label>Stok:</label>
                        <p>{{ $product->stock }}</p>
                    </div>
                    <div class="form-group">
                        <label>Dibuat Pada:</label>
                        <p>{{ $product->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="form-group">
                        <label>Diperbarui Pada:</label>
                        <p>{{ $product->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
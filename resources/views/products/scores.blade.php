@extends('layouts.admin')

@section('title', 'Input Nilai Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Nilai untuk: <strong>{{ $product->name }}</strong></h3>
                <div class="card-tools">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Produk</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('products.scores.update', $product->id) }}" method="POST">
                    @csrf

                    @if ($criteria->isEmpty())
                        <div class="alert alert-warning">
                            Tidak ada kriteria ditemukan untuk kategori 'produk'. Silakan <a href="{{ route('criteria.create') }}">tambahkan kriteria</a> terlebih dahulu.
                        </div>
                    @else
                        @foreach ($criteria as $criterion)
                            <div class="form-group">
                                <label for="score_{{ $criterion->id }}">{{ $criterion->name }} (Bobot: {{ $criterion->weight }}, Tipe: {{ ucfirst($criterion->type) }})</label>
                                <input type="number"
                                       name="scores[{{ $criterion->id }}]"
                                       id="score_{{ $criterion->id }}"
                                       class="form-control @error('scores.'.$criterion->id) is-invalid @enderror"
                                       step="any"
                                       value="{{ old('scores.'.$criterion->id, $scores->get($criterion->id)->value ?? '') }}"
                                       required>
                                @error('scores.'.$criterion->id)
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
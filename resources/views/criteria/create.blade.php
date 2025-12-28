@extends("layouts.admin")

@section("title", "Tambah Kriteria")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Kriteria</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route("criteria.store") }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kriteria</label>
                            <input type="text" name="name" id="name" class="form-control @error("name") is-invalid @enderror" value="{{ old("name") }}" required>
                            @error("name")
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="weight">Bobot</label>
                            <input type="number" name="weight" id="weight" class="form-control @error("weight") is-invalid @enderror" value="{{ old("weight") }}" step="0.01" min="0" max="1" required>
                            @error("weight")
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="type">Tipe Kriteria</label>
                            <select name="type" id="type" class="form-control @error("type") is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="benefit" {{ old("type") == "benefit" ? "selected" : "" }}>Benefit</option>
                                <option value="cost" {{ old("type") == "cost" ? "selected" : "" }}>Cost</option>
                            </select>
                            @error("type")
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <select name="category" id="category" class="form-control @error("category") is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="product" {{ old("category") == "product" ? "selected" : "" }}>Product</option>
                                <option value="store" {{ old("category") == "store" ? "selected" : "" }}>Store</option>
                            </select>
                            @error("category")
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route("criteria.index") }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

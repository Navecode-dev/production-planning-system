@extends('layouts.admin')

@section('title', 'Buat Jadwal Produksi Baru')

@push('styles')
<style>
    .store-card {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        background: #f8f9fa;
    }
    .product-row {
        background: white;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Buat Jadwal Produksi Baru</h3>
        <div class="card-tools">
            <a href="{{ route('production-schedule.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <form action="{{ route('production-schedule.store') }}" method="POST" id="scheduleForm">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <h5>Error:</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="schedule_name">Nama Jadwal <span class="text-danger">*</span></label>
                        <input type="text" name="schedule_name" id="schedule_name" class="form-control @error('schedule_name') is-invalid @enderror" value="{{ old('schedule_name') }}" required>
                        @error('schedule_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}">
                    </div>
                </div>
            </div>

            <hr>
            <h5>Pilih Toko dan Produk</h5>
            <p class="text-muted">Minimal pilih 2 toko untuk perhitungan ranking</p>

            <div id="storesContainer">
                <!-- Store cards akan ditambahkan di sini via JavaScript -->
            </div>

            <button type="button" class="btn btn-success" id="addStoreBtn">
                <i class="fas fa-plus"></i> Tambah Toko
            </button>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Jadwal
            </button>
            <a href="{{ route('production-schedule.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let storeIndex = 0;
const stores = @json($stores);
const products = @json($products);

console.log('Stores:', stores);
console.log('Products:', products);

$(document).ready(function() {
    console.log('Document ready');
    
    // Add first store by default
    addStore();

    // Bind add store button
    $('#addStoreBtn').on('click', function() {
        console.log('Add store clicked');
        addStore();
    });
});

function addStore() {
    console.log('Adding store index:', storeIndex);
    
    const storeHtml = `
        <div class="store-card" data-store-index="${storeIndex}">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm float-right remove-store">
                        <i class="fas fa-trash"></i> Hapus Toko
                    </button>
                    <h5>Toko #${storeIndex + 1}</h5>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilih Toko <span class="text-danger">*</span></label>
                        <select name="stores[${storeIndex}][store_id]" class="form-control" required>
                            <option value="">-- Pilih Toko --</option>
                            ${stores.map(store => `<option value="${store.id}">${store.name}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Deadline Kiriman (Hari) <span class="text-danger">*</span></label>
                        <input type="number" name="stores[${storeIndex}][deadline_days]" class="form-control" min="1" placeholder="Contoh: 7" required>
                        <small class="text-muted">7 hari = sangat prioritas, 30+ hari = tidak prioritas</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Produk yang Dipesan</label>
                <div class="products-container" data-store-index="${storeIndex}">
                    <!-- Product rows akan ditambahkan di sini -->
                </div>
                <button type="button" class="btn btn-sm btn-info add-product" data-store-index="${storeIndex}">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>
        </div>
    `;

    $('#storesContainer').append(storeHtml);

    // Add first product row automatically
    addProduct(storeIndex);
    
    // Bind remove store button
    $(`.store-card[data-store-index="${storeIndex}"] .remove-store`).on('click', function() {
        console.log('Remove store clicked');
        if ($('.store-card').length > 1) {
            $(this).closest('.store-card').remove();
            updateStoreNumbers();
        } else {
            alert('Minimal 1 toko harus ada!');
        }
    });

    // Bind add product button
    $(`.add-product[data-store-index="${storeIndex}"]`).on('click', function() {
        const idx = $(this).data('store-index');
        console.log('Add product clicked for store:', idx);
        addProduct(idx);
    });

    storeIndex++;
}

function addProduct(storeIdx) {
    console.log('Adding product for store:', storeIdx);
    
    const productIndex = $(`.products-container[data-store-index="${storeIdx}"] .product-row`).length;
    
    const productHtml = `
        <div class="product-row">
            <div class="row">
                <div class="col-md-5">
                    <select name="stores[${storeIdx}][products][${productIndex}][product_id]" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        ${products.map(product => `<option value="${product.id}">${product.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="stores[${storeIdx}][products][${productIndex}][quantity]" class="form-control" placeholder="Jumlah" step="0.01" min="0.01" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;

    $(`.products-container[data-store-index="${storeIdx}"]`).append(productHtml);

    // Bind remove product button
    $(`.products-container[data-store-index="${storeIdx}"] .product-row:last .remove-product`).on('click', function() {
        console.log('Remove product clicked');
        if ($(`.products-container[data-store-index="${storeIdx}"] .product-row`).length > 1) {
            $(this).closest('.product-row').remove();
        } else {
            alert('Minimal 1 produk harus ada per toko!');
        }
    });
}

function updateStoreNumbers() {
    $('.store-card').each(function(index) {
        $(this).find('h5').first().text(`Toko #${index + 1}`);
    });
}

// Form validation before submit
$('#scheduleForm').on('submit', function(e) {
    console.log('Form submitting...');
    
    const storeCount = $('.store-card').length;
    console.log('Store count:', storeCount);
    
    if (storeCount < 1) {
        e.preventDefault();
        alert('Minimal 1 toko diperlukan!');
        return false;
    }
    
    // Debug: print form data
    const formData = new FormData(this);
    console.log('Form data:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    return true;
});
</script>
@endpush
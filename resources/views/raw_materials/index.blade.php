@extends('layouts.admin')

@section('title', 'Manajemen Bahan Baku')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Bahan Baku</h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-materials.create') }}" class="btn btn-primary btn-sm">Tambah Bahan Baku Baru</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table id="raw-materials-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Baku</th>
                                <th>Harga / Palet (Rp)</th>
                                <th>Biaya Simpan (Rp)</th>
                                <th>Harga / Lembar (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#raw-materials-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('raw-materials.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'price_per_pallet', name: 'price_per_pallet', render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ') },
                    { data: 'storage_cost', name: 'storage_cost', render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ') },
                    { data: 'price_per_sheet', name: 'price_per_sheet', render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ') },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
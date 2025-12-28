@extends('layouts.admin')

@section('title', 'Manajemen Toko')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Toko</h3>
                    <div class="card-tools">
                        <a href="{{ route('stores.create') }}" class="btn btn-primary btn-sm">Tambah Toko Baru</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    @endif
                    <table id="stores-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Area Penjualan</th>
                                <th>Kontak</th>
                                <th>Penanggung Jawab</th>
                                <th>Input Skor</th>
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
            $('#stores-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stores.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, title: 'No' },
                    { data: 'name', name: 'name', title: 'Nama' },
                    { data: 'address', name: 'address', title: 'Alamat' },
                    { data: 'sales_area', name: 'sales_area', title: 'Area Penjualan' },
                    { data: 'contact', name: 'contact', title: 'Kontak' },
                    { data: 'person_in_charge', name: 'person_in_charge', title: 'PIC' },
                    { data: 'input_score', name: 'input_score', orderable: false, searchable: false, title: 'Input Skor' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Aksi' },
                ]
            });
        });
    </script>
@endpush
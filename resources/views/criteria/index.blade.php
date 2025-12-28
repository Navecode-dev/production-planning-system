@extends("layouts.admin")

@section("title", "Master Data Kriteria")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kriteria</h3>
                    <div class="card-tools">
                        <a href="{{ route("criteria.create") }}" class="btn btn-primary btn-sm">Tambah Kriteria</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session("success"))
                        <div class="alert alert-success">{{ session("success") }}</div>
                    @endif
                    <table id="criteria-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Dibuat Pada</th>
                                <th>Diperbarui Pada</th>
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

@push("scripts")
    <script>
        $(function () {
            $("#criteria-table").DataTable({
                processing: true,                serverSide: true,
                ajax: "{{ route('criteria.index') }}",
                columns: [
                    { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                    { data: "name", name: "name", title: "Nama" },
                    { data: "weight", name: "weight", title: "Bobot" },
                    { data: "type", name: "type", title: "Tipe" },
                    { data: "category", name: "category", title: "Kategori" },
                    {
                        data: "created_at",
                        name: "created_at",
                        render: function (data, type, row) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: "updated_at",
                        name: "updated_at",
                        render: function (data, type, row) {
                            return moment(data).format('DD/MM/YYYY');
                        },
                        title: "Terakhir Diubah"
                    },
                    { data: "action", name: "action", orderable: false, searchable: false, title: "Aksi" },
                ]
            });
        });
    </script>
@endpush

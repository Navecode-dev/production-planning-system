@extends('layouts.admin')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jadwal Produksi</h3>
        <div class="card-tools">
            <a href="{{ route('production-schedule.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Jadwal Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if($schedules->isEmpty())
            <div class="alert alert-info">
                Belum ada jadwal produksi. <a href="{{ route('production-schedule.create') }}">Buat jadwal baru</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Jadwal</th>
                            <th>Jumlah Toko</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                            <td>
                                <strong>{{ $schedule->schedule_name }}</strong>
                                @if($schedule->description)
                                <br><small class="text-muted">{{ Str::limit($schedule->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $schedule->scheduleStores ? $schedule->scheduleStores->count() : 0 }} Toko</td>
                            <td>
                                @if($schedule->status === 'draft')
                                    <span class="badge badge-warning">Draft</span>
                                @elseif($schedule->status === 'calculated')
                                    <span class="badge badge-success">Selesai Dihitung</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($schedule->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('production-schedule.show', $schedule->id) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Detail & Data Toko">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($schedule->status === 'calculated')
                                    <a href="{{ route('production-schedule.result', $schedule->id) }}" 
                                       class="btn btn-success btn-sm" 
                                       title="Lihat Hasil & Ranking">
                                        <i class="fas fa-trophy"></i>
                                    </a>
                                @endif
                                <form action="{{ route('production-schedule.destroy', $schedule->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
@endsection
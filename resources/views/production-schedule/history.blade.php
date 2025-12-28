@extends('layouts.admin')

@section('title', 'Riwayat Jadwal Produksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Jadwal Produksi</h3>
        <div class="card-tools">
            <a href="{{ route('production-schedule.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Jadwal Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($schedules->isEmpty())
            <div class="alert alert-info">
                Belum ada riwayat jadwal produksi yang telah dihitung.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Jadwal</th>
                            <th>Jumlah Toko</th>
                            <th>Toko Prioritas #1</th>
                            <th>Tanggal Dihitung</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                            <td>
                                <strong>{{ $schedule->schedule_name }}</strong>
                                @if($schedule->description)
                                <br><small class="text-muted">{{ Str::limit($schedule->description, 40) }}</small>
                                @endif
                            </td>
                            <td>{{ $schedule->scheduleStores->count() }} Toko</td>
                            <td>
                                @php
                                    $topStore = $schedule->scheduleStores->where('rank_position', 1)->first();
                                @endphp
                                @if($topStore)
                                    <strong class="text-success">🥇 {{ $topStore->store->name }}</strong><br>
                                    <small class="text-muted">Score: {{ number_format($topStore->rank_score, 4) }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $schedule->calculated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('production-schedule.result', $schedule->id) }}" class="btn btn-success btn-sm" title="Lihat Hasil">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('production-schedule.show', $schedule->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('production-schedule.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
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
@endsection
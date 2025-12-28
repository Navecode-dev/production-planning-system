@extends('layouts.admin')

@section('title', 'Riwayat Perhitungan ELECTRE Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Perhitungan Peringkat Produk</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('electre.product.history') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('electre.product.history') }}" class="btn btn-secondary">Reset</a>
                                <a href="{{ route('reports.electre.product.history.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Perhitungan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($calculations as $index => $calculation)
                                <tr>
                                    <td>{{ $calculations->firstItem() + $index }}</td>
                                    <td>{{ $calculation->created_at->format('d F Y H:i:s') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm view-details"
                                                data-ranking="{{ json_encode($calculation->ranking) }}"
                                                data-date="{{ $calculation->created_at->format('d F Y H:i:s') }}"
                                                data-toggle="modal"
                                                data-target="#detailModal">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada riwayat perhitungan ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $calculations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Hasil Peringkat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Tanggal Perhitungan:</strong> <span id="modal-calculation-date"></span></p>
        <div id="modal-ranking-table-container" class="table-responsive">
            {{-- Tabel detail akan dimasukkan di sini oleh JavaScript --}}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var rankingData = button.data('ranking');
        var calculationDate = button.data('date');

        var modal = $(this);
        modal.find('#modal-calculation-date').text(calculationDate);

        // Mengonversi objek JSON ke array, lalu mengurutkannya berdasarkan skor (descending)
        var sortedRanking = Object.entries(rankingData).sort(function(a, b) {
            return b[1] - a[1];
        });

        var tableHtml = '<table class="table table-bordered table-hover">';
        tableHtml += '<thead class="thead-light"><tr><th>Peringkat</th><th>Produk</th><th>Skor</th></tr></thead>';
        tableHtml += '<tbody>';

        if (sortedRanking.length > 0) {
            var rank = 1;
            sortedRanking.forEach(function(item) {
                var productName = item[0];
                var score = item[1];
                tableHtml += '<tr><td>' + (rank++) + '</td><td>' + productName + '</td><td>' + score + '</td></tr>';
            });
        } else {
            tableHtml += '<tr><td colspan="3" class="text-center">Tidak ada data peringkat.</td></tr>';
        }

        tableHtml += '</tbody></table>';

        modal.find('#modal-ranking-table-container').html(tableHtml);
    });
});
</script>
@endpush
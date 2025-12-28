@extends("layouts.admin")

@section("title", "Perhitungan ELECTRE")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perhitungan ELECTRE</h3>
                </div>
                <div class="card-body">
                    @if (session("error"))
                        <div class="alert alert-danger">{{ session("error") }}</div>
                    @endif

                    <form action="{{ route("electre.calculate") }}" method="POST">
                        @csrf
                        <h4>Alternatif</h4>
                        @if($alternatives->isEmpty())
                            <p>Tidak ada alternatif ditemukan. Mohon tambahkan alternatif terlebih dahulu.</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alternatives as $alternative)
                                        <tr>
                                            <td>{{ $alternative->name }}</td>
                                            <td>{{ $alternative->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <h4 class="mt-4">Kriteria</h4>
                        @if($criteria->isEmpty())
                            <p>Tidak ada kriteria ditemukan. Mohon tambahkan kriteria terlebih dahulu.</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Bobot</th>
                                        <th>Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criteria as $criterion)
                                        <tr>
                                            <td>{{ $criterion->name }}</td>
                                            <td>{{ $criterion->weight }}</td>
                                            <td>{{ $criterion->type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <h4 class="mt-4">Nilai Keputusan</h4>
                        @if($decisionValues->isEmpty())
                            <p>Tidak ada nilai keputusan ditemukan. Mohon tambahkan nilai keputusan terlebih dahulu.</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach($criteria as $criterion)
                                            <th>{{ $criterion->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alternatives as $alternative)
                                        <tr>
                                            <td>{{ $alternative->name }}</td>
                                            @foreach($criteria as $criterion)
                                                <td>
                                                    {{ $decisionValues->where("alternative_id", $alternative->id)->where("criteria_id", $criterion->id)->first()->value ?? "N/A" }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if(!$alternatives->isEmpty() && !$criteria->isEmpty())
                            <button type="submit" class="btn btn-primary mt-3">Hitung ELECTRE</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
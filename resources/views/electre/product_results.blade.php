@extends('layouts.admin')

@section('title', 'ELECTRE - Hasil Peringkat Produk')

@section('styles')
<style>
    .matrix-table th,
    .matrix-table td {
        text-align: center;
        vertical-align: middle;
    }

    .matrix-table .header-cell {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .matrix-table .product-name {
        text-align: left;
        font-weight: bold;
    }
    .formula-section {
        margin-top: 20px;
        margin-bottom: 20px;
        padding: 15px;
        background-color: #f9f9f9;
        border: 1px solid #e3e3e3;
        border-radius: 5px;
    }
    .formula-section h4 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #333;
    }
    .formula-text {
        font-size: 0.95em;
        color: #555;
        margin-top: 5px;
        margin-bottom: 15px;
        display: block;
    }
    .formula-code {
        display: block;
        text-align: center;
        margin: 15px 0;
        font-size: 1.1em;
        font-family: monospace;
        background-color: #eee;
        padding: 10px;
        border-radius: 3px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hasil Perhitungan ELECTRE untuk Produk</h3>
                <div class="card-tools">
                    <a href="{{ route('electre.product.index') }}" class="btn btn-secondary btn-sm">Kembali ke Perhitungan</a>
                </div>
            </div>
            <div class="card-body">
                @php renderMatrix('Langkah 0: Matriks Keputusan (Skor Awal)', $decisionMatrix, $products, $criteria, '%.2f'); @endphp
                <div class="formula-section">
                    <h4>1. Matriks Keputusan Mentah (X)</h4>
                    <p class="formula-text">Matriks ini berisi skor mentah setiap produk terhadap setiap kriteria. Contoh representasi matriks:</p>
                    <code class="formula-code">
                        X = <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ x<sub>11</sub>&nbsp;&nbsp;x<sub>12</sub>&nbsp;&nbsp;...&nbsp;&nbsp;x<sub>1n</sub> ] <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ x<sub>21</sub>&nbsp;&nbsp;x<sub>22</sub>&nbsp;&nbsp;...&nbsp;&nbsp;x<sub>2n</sub> ] <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ ...&nbsp;...&nbsp;&nbsp;...&nbsp;&nbsp;...&nbsp;] <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ x<sub>m1</sub>&nbsp;&nbsp;x<sub>m2</sub>&nbsp;&nbsp;...&nbsp;&nbsp;x<sub>mn</sub> ]
                    </code>
                    <p class="formula-text">Di mana x<sub>ij</sub> adalah skor alternatif i pada kriteria j.</p>
                </div>

                @php renderMatrix('Langkah 1: Matriks Keputusan Ternormalisasi', $normalizedMatrix, $products, $criteria); @endphp
                <div class="formula-section">
                    <h4>2. Matriks Keputusan Ternormalisasi (R)</h4>
                    <p class="formula-text">Setiap elemen matriks dinormalisasi menggunakan rumus:</p>
                    <code class="formula-code">r<sub>ij</sub> = x<sub>ij</sub> / &radic;( &sum;<sub>k=1 to m</sub> x<sub>kj</sub><sup>2</sup> )</code>
                    <p class="formula-text">Di mana x<sub>ij</sub> adalah skor mentah alternatif i pada kriteria j, dan m adalah jumlah alternatif.</p>
                </div>

                @php renderMatrix('Langkah 2: Matriks Ternormalisasi Terbobot', $weightedMatrix, $products, $criteria); @endphp
                <div class="formula-section">
                    <h4>3. Matriks Ternormalisasi Terbobot (V)</h4>
                    <p class="formula-text">Setiap elemen matriks ternormalisasi dikalikan dengan bobot kriteria yang sesuai:</p>
                    <code class="formula-code">v<sub>ij</sub> = w<sub>j</sub> * r<sub>ij</sub></code>
                    <p class="formula-text">Di mana w<sub>j</sub> adalah bobot kriteria j, dan r<sub>ij</sub> adalah nilai ternormalisasi.</p>
                </div>

                <div class="formula-section">
                    <h4>4. Penentuan Himpunan Konkordansi dan Diskordansi</h4>
                    <p class="formula-text">
                        Pada langkah ini, untuk setiap pasangan alternatif (k, l), diidentifikasi kriteria-kriteria di mana alternatif k lebih baik atau sama dengan alternatif l (himpunan konkordansi) dan kriteria-kriteria di mana alternatif k lebih buruk dari alternatif l (himpunan diskordansi).
                    </p>
                    <p class="formula-text">
                        Himpunan Konkordansi C<sub>kl</sub>: Kriteria j dimana v<sub>kj</sub> &ge; v<sub>lj</sub> (untuk benefit) atau v<sub>kj</sub> &le; v<sub>lj</sub> (untuk cost).
                    </p>
                    <p class="formula-text">
                        Himpunan Diskordansi D<sub>kl</sub>: Kriteria j dimana v<sub>kj</sub> &lt; v<sub>lj</sub> (untuk benefit) atau v<sub>kj</sub> &gt; v<sub>lj</sub> (untuk cost).
                    </p>
                </div>
                @php renderComparisonMatrix('Langkah 3 & 4a: Matriks Konkordansi (C)', $concordanceMatrix, $products); @endphp
                <div class="formula-section">
                    <h4>Matriks Konkordansi (C)</h4>
                    <p class="formula-text">
                        Matriks ini menunjukkan seberapa kuat alternatif k mendominasi alternatif l berdasarkan kriteria konkordansi. Dihitung sebagai jumlah bobot kriteria dalam himpunan konkordansi.
                    </p>
                    <code class="formula-code">c<sub>kl</sub> = &sum; (w<sub>j</sub> untuk j &isin; C<sub>kl</sub>)</code>
                </div>
                @php renderComparisonMatrix('Langkah 3 & 4b: Matriks Diskordansi (D)', $discordanceMatrix, $products); @endphp
                <div class="formula-section">
                    <h4>Matriks Diskordansi (D)</h4>
                    <p class="formula-text">
                        Matriks ini menunjukkan seberapa kuat alternatif k tidak mendominasi alternatif l berdasarkan kriteria diskordansi. Dihitung berdasarkan perbedaan maksimum skor terbobot pada kriteria diskordansi, dinormalisasi oleh perbedaan maksimum keseluruhan.
                    </p>
                    <code class="formula-code">d<sub>kl</sub> = max(|v<sub>kj</sub> &ndash; v<sub>lj</sub>| untuk j &isin; D<sub>kl</sub>) / max(|v<sub>kj</sub> &ndash; v<sub>lj</sub>| untuk semua j)</code>
                </div>

                <div class="formula-section">
                    <h4>5. Penentuan Matriks Dominansi (Konkordansi & Diskordansi)</h4>
                    <p class="formula-text">
                        Pada langkah ini, matriks dominansi dibentuk berdasarkan ambang batas yang dihitung dari matriks konkordansi dan diskordansi.
                    </p>
                </div>
                @php renderComparisonMatrix('Langkah 5a: Matriks Dominansi Konkordansi (F)', $concordanceDominance, $products, '%d', $concordanceThreshold); @endphp
                <div class="formula-section">
                    <h4>Matriks Dominansi Konkordansi (F)</h4>
                    <p class="formula-text">
                        Matriks ini menunjukkan apakah alternatif k mendominasi alternatif l berdasarkan indeks konkordansi dan ambang batas konkordansi (c*).
                    </p>
                    <code class="formula-code">f<sub>kl</sub> = 1 jika c<sub>kl</sub> &ge; c* (Ambang Batas Konkordansi), else 0</code>
                    <p class="formula-text">Ambang Batas Konkordansi (c*) = <strong>{{ number_format($concordanceThreshold, 4) }}</strong></p>
                </div>
                @php renderComparisonMatrix('Langkah 5b: Matriks Dominansi Diskordansi (G)', $discordanceDominance, $products, '%d', $discordanceThreshold); @endphp
                <div class="formula-section">
                    <h4>Matriks Dominansi Diskordansi (G)</h4>
                    <p class="formula-text">
                        Matriks ini menunjukkan apakah alternatif k mendominasi alternatif l berdasarkan indeks diskordansi dan ambang batas diskordansi (d*).
                    </p>
                    <code class="formula-code">g<sub>kl</sub> = 1 jika d<sub>kl</sub> &le; d* (Ambang Batas Diskordansi), else 0</code>
                    <p class="formula-text">Ambang Batas Diskordansi (d*) = <strong>{{ number_format($discordanceThreshold, 4) }}</strong></p>
                </div>

                @php renderComparisonMatrix('Langkah 6: Matriks Dominansi Agregat (E)', $aggregateDominanceMatrix, $products, '%d'); @endphp
                <div class="formula-section">
                    <h4>6. Matriks Dominansi Agregat (E)</h4>
                    <p class="formula-text">
                        Matriks ini menunjukkan apakah alternatif k secara keseluruhan mendominasi alternatif l. Dominasi terjadi jika kriteria konkordansi dan diskordansi terpenuhi.
                    </p>
                    <code class="formula-code">e<sub>kl</sub> = f<sub>kl</sub> * g<sub>kl</sub></code>
                </div>

                <div class="mt-4">
                    <h4>Langkah 7: Peringkat Produk Akhir</h4>
                    <p class="formula-text">
                        Peringkat dihitung berdasarkan jumlah 'kemenangan' (dominasi) dikurangi jumlah 'kekalahan' dari matriks dominansi agregat.
                    </p>
                    <code class="formula-code">Skor = (Jumlah Kemenangan) - (Jumlah Kekalahan)</code>
                    @if(empty($ranking))
                    <p>Tidak ada peringkat tersedia.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Produk</th>
                                    <th>Skor (Menang - Kalah)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rank = 1; @endphp
                                @foreach($ranking as $productName => $score)
                                <tr>
                                    <td>{{ $rank++ }}</td>
                                    <td>{{ $productName }}</td>
                                    <td>{{ $score }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
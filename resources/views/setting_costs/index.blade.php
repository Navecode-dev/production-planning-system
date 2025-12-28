@extends('layouts.admin')

@section('title', 'Pengaturan Biaya Operasional')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Biaya Pengaturan</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('setting-costs.update') }}" method="POST">
                    @csrf
                    @if($setupCosts->isEmpty())
                        <div class="alert alert-warning">
                            Tidak ada biaya pengaturan ditemukan. Harap jalankan seeder: <code>php artisan db:seed --class=SettingCostSeeder</code>
                        </div>
                    @else
                        @foreach ($setupCosts as $index => $cost)
                            <div class="form-group row">
                                <label for="{{ $cost->key }}" class="col-sm-4 col-form-label">{{ $cost->label }}</label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="settings[{{ $index }}][key]" value="{{ $cost->key }}">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="settings[{{ $index }}][value]"
                                               id="{{ $cost->key }}"
                                               class="form-control cost-input @error('settings.'.$index.'.value') is-invalid @enderror"
                                               value="{{ old('settings.'.$index.'.value', $cost->value) }}"
                                               step="1"
                                               required>
                                    </div>
                                    @error('settings.'.$index.'.value')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Total</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold">Rp</span>
                                    </div>
                                    <input type="text" id="total-cost" class="form-control font-weight-bold" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-8 offset-sm-4">
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        function calculateTotal() {
            let total = 0;
            $('.cost-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            // Format sebagai mata uang
            $('#total-cost').val(new Intl.NumberFormat('id-ID').format(total));
        }

        // Hitung saat halaman dimuat
        calculateTotal();

        // Hitung ulang saat input biaya berubah
        $('.cost-input').on('input', calculateTotal);
    });
</script>
@endpush
<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\ProductionScheduleStore;
use App\Models\ProductionScheduleProduct;
use App\Models\ProductionScheduleScore;
use App\Models\Store;
use App\Models\Product;
use App\Models\Criteria;
use App\Models\StoreScore;
use App\Models\ElectreProductCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionScheduleController extends Controller
{
    // Halaman utama jadwal produksi
    public function index()
    {
        $schedules = ProductionSchedule::with('scheduleStores.store')
        ->latest()
        ->paginate(10);
    
        return view('production-schedule.index', compact('schedules'));
    }

    // Form create jadwal baru
    public function create()
    {
        $stores = Store::all();
        $products = Product::all();
        
        // Ambil ranking produk dari ELECTRE terakhir
        $latestElectreProduct = ElectreProductCalculation::latest()->first();
        $productRankings = [];
        if ($latestElectreProduct) {
            $rankings = is_array($latestElectreProduct->ranking) 
            ? $latestElectreProduct->ranking 
            : json_decode($latestElectreProduct->ranking, true);

            // Convert score to ranking position
            arsort($rankings);
            $position = 1;
            foreach ($rankings as $productName => $score) {
                $productRankings[$productName] = $position++;
            }
        }
        
        return view('production-schedule.create', compact('stores', 'products', 'productRankings'));
    }

    // Simpan jadwal baru (belum dihitung)
    public function store(Request $request)
    {
        // Debug: cek data yang masuk
        \Log::info('Request Data:', $request->all());
    
        $request->validate([
            'schedule_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stores' => 'required|array|min:1', // Ubah dari min:2 ke min:1 untuk testing
            'stores.*.store_id' => 'required|exists:stores,id',
            'stores.*.deadline_days' => 'required|integer|min:1',
            'stores.*.products' => 'required|array|min:1',
            'stores.*.products.*.product_id' => 'required|exists:products,id',
            'stores.*.products.*.quantity' => 'required|numeric|min:0.01',
        ]);
    
        DB::beginTransaction();
        try {
            // 1. Buat jadwal produksi
            $schedule = ProductionSchedule::create([
                'schedule_name' => $request->schedule_name,
                'description' => $request->description,
                'status' => 'draft',
            ]);
    
            // 2. Loop setiap toko
            foreach ($request->stores as $storeData) {
                $totalQty = 0;
                $productVariety = count($storeData['products']);
    
                // Hitung total qty
                foreach ($storeData['products'] as $productData) {
                    $totalQty += floatval($productData['quantity']);
                }
    
                // Simpan data toko dalam jadwal
                $scheduleStore = ProductionScheduleStore::create([
                    'production_schedule_id' => $schedule->id,
                    'store_id' => $storeData['store_id'],
                    'deadline_days' => $storeData['deadline_days'],
                    'total_qty' => $totalQty,
                    'product_variety' => $productVariety,
                ]);
    
                // Simpan produk-produk untuk toko ini
                foreach ($storeData['products'] as $productData) {
                    ProductionScheduleProduct::create([
                        'production_schedule_store_id' => $scheduleStore->id,
                        'product_id' => $productData['product_id'],
                        'quantity' => floatval($productData['quantity']),
                    ]);
                }
    
                // 3. Simpan nilai kriteria
                // Total QTY
                ProductionScheduleScore::create([
                    'production_schedule_store_id' => $scheduleStore->id,
                    'criteria_id' => 7,
                    'criteria_name' => 'Total QTY Orderan',
                    'criteria_type' => 'benefit',
                    'raw_value' => $totalQty,
                    'normalized_value' => $this->normalizeQty($totalQty),
                    'weight' => 0.30,
                    'source' => 'order',
                ]);
    
                // Variasi Produk
                ProductionScheduleScore::create([
                    'production_schedule_store_id' => $scheduleStore->id,
                    'criteria_id' => 9,
                    'criteria_name' => 'Variasi Produk',
                    'criteria_type' => 'benefit',
                    'raw_value' => $productVariety,
                    'normalized_value' => $this->normalizeVariety($productVariety),
                    'weight' => 0.20,
                    'source' => 'order',
                ]);
    
                // Deadline
                ProductionScheduleScore::create([
                    'production_schedule_store_id' => $scheduleStore->id,
                    'criteria_id' => 10,
                    'criteria_name' => 'Deadline Kiriman',
                    'criteria_type' => 'cost',
                    'raw_value' => $storeData['deadline_days'],
                    'normalized_value' => $this->normalizeDeadline($storeData['deadline_days']),
                    'weight' => 0.25,
                    'source' => 'order',
                ]);
    
                // Jarak (dari master) - optional
                $jarakScore = StoreScore::where('store_id', $storeData['store_id'])
                    ->where('criteria_id', 8)
                    ->first();
    
                if ($jarakScore) {
                    ProductionScheduleScore::create([
                        'production_schedule_store_id' => $scheduleStore->id,
                        'criteria_id' => 8,
                        'criteria_name' => 'Jarak',
                        'criteria_type' => 'cost',
                        'raw_value' => $jarakScore->value,
                        'normalized_value' => intval($jarakScore->value),
                        'weight' => 0.15,
                        'source' => 'master',
                    ]);
                }
    
                // Frekuensi (dari master) - optional
                $frekuensiScore = StoreScore::where('store_id', $storeData['store_id'])
                    ->where('criteria_id', 11)
                    ->first();
    
                if ($frekuensiScore) {
                    ProductionScheduleScore::create([
                        'production_schedule_store_id' => $scheduleStore->id,
                        'criteria_id' => 11,
                        'criteria_name' => 'Frekuensi Permintaan',
                        'criteria_type' => 'benefit',
                        'raw_value' => $frekuensiScore->value,
                        'normalized_value' => intval($frekuensiScore->value),
                        'weight' => 0.10,
                        'source' => 'master',
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('production-schedule.show', $schedule->id)
                ->with('success', 'Jadwal produksi berhasil dibuat!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error creating schedule: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->with('error', 'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')')
                ->withInput();
        }
    }
    
    // Simpan nilai kriteria hybrid (master + order)
    private function saveHybridScores(
        $scheduleStore, 
        $storeId,
        $totalQty, 
        $productVariety, 
        $deadlineDays,
        $criteriaJarak,
        $criteriaFrekuensi,
        $criteriaTotalQty,
        $criteriaVariasi,
        $criteriaDeadline
    ) {
        $scores = [];

        // 1. Ambil nilai dari store_scores (Jarak & Frekuensi)
        if ($criteriaJarak) {
            $storeScore = StoreScore::where('store_id', $storeId)
                ->where('criteria_id', $criteriaJarak->id)
                ->first();
            
            $scores[] = [
                'criteria_id' => $criteriaJarak->id,
                'criteria_name' => $criteriaJarak->name,
                'criteria_type' => $criteriaJarak->type,
                'raw_value' => $storeScore ? $storeScore->value : 0,
                'normalized_value' => $storeScore ? (int)$storeScore->value : 0,
                'weight' => $criteriaJarak->weight,
                'source' => 'master',
            ];
        }

        if ($criteriaFrekuensi) {
            $storeScore = StoreScore::where('store_id', $storeId)
                ->where('criteria_id', $criteriaFrekuensi->id)
                ->first();
            
            $scores[] = [
                'criteria_id' => $criteriaFrekuensi->id,
                'criteria_name' => $criteriaFrekuensi->name,
                'criteria_type' => $criteriaFrekuensi->type,
                'raw_value' => $storeScore ? $storeScore->value : 0,
                'normalized_value' => $storeScore ? (int)$storeScore->value : 0,
                'weight' => $criteriaFrekuensi->weight,
                'source' => 'master',
            ];
        }

        // 2. Nilai dari pesanan (Total Qty, Variasi, Deadline)
        if ($criteriaTotalQty) {
            $qtyNormalized = $this->normalizeQty($totalQty);
            $scores[] = [
                'criteria_id' => $criteriaTotalQty->id,
                'criteria_name' => $criteriaTotalQty->name,
                'criteria_type' => $criteriaTotalQty->type,
                'raw_value' => $totalQty,
                'normalized_value' => $qtyNormalized,
                'weight' => $criteriaTotalQty->weight,
                'source' => 'order',
            ];
        }

        if ($criteriaVariasi) {
            $varietyNormalized = $this->normalizeVariety($productVariety);
            $scores[] = [
                'criteria_id' => $criteriaVariasi->id,
                'criteria_name' => $criteriaVariasi->name,
                'criteria_type' => $criteriaVariasi->type,
                'raw_value' => $productVariety,
                'normalized_value' => $varietyNormalized,
                'weight' => $criteriaVariasi->weight,
                'source' => 'order',
            ];
        }

        if ($criteriaDeadline) {
            $deadlineNormalized = $this->normalizeDeadline($deadlineDays);
            $scores[] = [
                'criteria_id' => $criteriaDeadline->id,
                'criteria_name' => $criteriaDeadline->name,
                'criteria_type' => $criteriaDeadline->type,
                'raw_value' => $deadlineDays,
                'normalized_value' => $deadlineNormalized,
                'weight' => $criteriaDeadline->weight,
                'source' => 'order',
            ];
        }

        // Simpan semua scores
        foreach ($scores as $scoreData) {
            ProductionScheduleScore::create(array_merge(
                ['production_schedule_store_id' => $scheduleStore->id],
                $scoreData
            ));
        }
    }

    // Fungsi konversi Total Qty ke skala 1-5
    private function normalizeQty($qty)
    {
        if ($qty >= 100) return 5;
        if ($qty >= 70) return 4;
        if ($qty >= 50) return 3;
        if ($qty >= 35) return 2;
        return 1;
    }

    // Fungsi konversi Variasi Produk ke skala 1-5
    private function normalizeVariety($variety)
    {
        if ($variety >= 7) return 5;
        if ($variety >= 5) return 4;
        if ($variety >= 3) return 3;
        if ($variety >= 2) return 2;
        return 1;
    }

    // Fungsi konversi Deadline ke skala 1-5 (makin cepat makin tinggi)
    private function normalizeDeadline($days)
    {
        if ($days <= 7) return 5;
        if ($days <= 14) return 4;
        if ($days <= 21) return 3;
        if ($days <= 28) return 2;
        return 1;
    }

    // Detail jadwal produksi
    public function show($id)
    {
        $schedule = ProductionSchedule::with([
            'scheduleStores.store',
            'scheduleStores.products.product',
            'scheduleStores.scores'
        ])->findOrFail($id);
    
        return view('production-schedule.show', compact('schedule'));
    }

    // Hitung ELECTRE
    public function calculate($id)
    {
        $schedule = ProductionSchedule::with(['scheduleStores.scores'])->findOrFail($id);

        DB::beginTransaction();
        try {
            $scheduleStores = $schedule->scheduleStores;
            $storeCount = $scheduleStores->count();

            if ($storeCount < 2) {
                return back()->with('error', 'Minimal 2 toko diperlukan untuk perhitungan ranking.');
            }

            // Ambil data matriks keputusan
            $matrix = [];
            $weights = [];
            $types = [];

            foreach ($scheduleStores as $idx => $scheduleStore) {
                $scores = $scheduleStore->scores;
                $matrix[$idx] = [];

                foreach ($scores as $score) {
                    $matrix[$idx][] = $score->normalized_value;
                    if ($idx === 0) {
                        $weights[] = (float)$score->weight;
                        $types[] = $score->criteria_type;
                    }
                }
            }

            // Hitung ELECTRE
            $rankings = $this->calculateElectre($matrix, $weights, $types);

            // Update rank_score dan rank_position
            foreach ($scheduleStores as $idx => $scheduleStore) {
                $scheduleStore->update([
                    'rank_score' => $rankings[$idx]['score'],
                    'rank_position' => $rankings[$idx]['position'],
                ]);
            }

            // Update status jadwal
            $schedule->update([
                'status' => 'calculated',
                'calculated_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('production-schedule.result', $schedule->id)
                ->with('success', 'Perhitungan ELECTRE berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghitung: ' . $e->getMessage());
        }
    }

    // Algoritma ELECTRE
    private function calculateElectre($matrix, $weights, $types)
    {
        $alternativeCount = count($matrix);
        $criteriaCount = count($matrix[0]);

        // 1. Normalisasi matriks
        $normalized = [];
        for ($j = 0; $j < $criteriaCount; $j++) {
            $sum = 0;
            for ($i = 0; $i < $alternativeCount; $i++) {
                $sum += pow($matrix[$i][$j], 2);
            }
            $sqrt = sqrt($sum);

            for ($i = 0; $i < $alternativeCount; $i++) {
                $normalized[$i][$j] = $sqrt > 0 ? $matrix[$i][$j] / $sqrt : 0;
            }
        }

        // 2. Pembobotan matriks ternormalisasi
        $weighted = [];
        for ($i = 0; $i < $alternativeCount; $i++) {
            for ($j = 0; $j < $criteriaCount; $j++) {
                $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
            }
        }

        // 3. Hitung Concordance dan Discordance Set
        $concordance = [];
        $discordance = [];

        for ($i = 0; $i < $alternativeCount; $i++) {
            for ($k = 0; $k < $alternativeCount; $k++) {
                if ($i !== $k) {
                    $concordance[$i][$k] = 0;
                    $discordance[$i][$k] = 0;

                    $maxDiff = 0;

                    for ($j = 0; $j < $criteriaCount; $j++) {
                        $diff = $weighted[$i][$j] - $weighted[$k][$j];

                        // Concordance
                        if (($types[$j] === 'benefit' && $weighted[$i][$j] >= $weighted[$k][$j]) ||
                            ($types[$j] === 'cost' && $weighted[$i][$j] <= $weighted[$k][$j])) {
                            $concordance[$i][$k] += $weights[$j];
                        }

                        // Discordance
                        if (abs($diff) > $maxDiff) {
                            $maxDiff = abs($diff);
                        }
                    }

                    // Normalisasi discordance
                    $maxAll = 0;
                    for ($j = 0; $j < $criteriaCount; $j++) {
                        $diff = abs($weighted[$i][$j] - $weighted[$k][$j]);
                        if ($diff > $maxAll) {
                            $maxAll = $diff;
                        }
                    }
                    $discordance[$i][$k] = $maxAll > 0 ? $maxDiff / $maxAll : 0;
                }
            }
        }

        // 4. Hitung Net Concordance dan Net Discordance
        $netConcordance = [];
        $netDiscordance = [];

        for ($i = 0; $i < $alternativeCount; $i++) {
            $netConcordance[$i] = 0;
            $netDiscordance[$i] = 0;

            for ($k = 0; $k < $alternativeCount; $k++) {
                if ($i !== $k) {
                    $netConcordance[$i] += ($concordance[$i][$k] - $concordance[$k][$i]);
                    $netDiscordance[$i] += ($discordance[$i][$k] - $discordance[$k][$i]);
                }
            }
        }

        // 5. Hitung Net Score
        $scores = [];
        for ($i = 0; $i < $alternativeCount; $i++) {
            $scores[$i] = $netConcordance[$i] - $netDiscordance[$i];
        }

        // 6. Ranking
        $rankings = [];
        foreach ($scores as $idx => $score) {
            $rankings[] = [
                'index' => $idx,
                'score' => $score,
            ];
        }

        usort($rankings, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $result = [];
        foreach ($rankings as $position => $data) {
            $result[$data['index']] = [
                'score' => round($data['score'], 4),
                'position' => $position + 1,
            ];
        }

        return $result;
    }

    // Halaman hasil perhitungan
    public function result($id)
    {
        $schedule = ProductionSchedule::with([
            'scheduleStores' => function ($query) {
                $query->orderBy('rank_position');
            },
            'scheduleStores.store',
            'scheduleStores.products.product',
            'scheduleStores.scores.criteria'
        ])->findOrFail($id);

        if ($schedule->status !== 'calculated') {
            return redirect()->route('production-schedule.show', $id)
                ->with('warning', 'Jadwal belum dihitung. Silakan lakukan perhitungan terlebih dahulu.');
        }

        // Ambil ranking produk dari ELECTRE terakhir
        $latestElectreProduct = ElectreProductCalculation::latest()->first();
        $productRankings = [];
        if ($latestElectreProduct) {
            $rankings = is_array($latestElectreProduct->ranking) 
            ? $latestElectreProduct->ranking 
            : json_decode($latestElectreProduct->ranking, true);
            arsort($rankings);
            $position = 1;
            foreach ($rankings as $productName => $score) {
                $productRankings[$productName] = [
                    'position' => $position++,
                    'score' => $score
                ];
            }
        }

        return view('production-schedule.result', compact('schedule', 'productRankings'));
    }

    // History
    public function history()
    {
        $schedules = ProductionSchedule::with('scheduleStores.store')
            ->where('status', 'calculated')
            ->latest('calculated_at')
            ->paginate(15);

        return view('production-schedule.history', compact('schedules'));
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $schedule = ProductionSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('production-schedule.index')
            ->with('success', 'Jadwal produksi berhasil dihapus.');
    }
}
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(
    '/',
    function () {
        return redirect()->route('dashboard');
    }
);

Route::get(
    '/dashboard', [App\Http\Controllers\DashboardController::class, "index"]
)->middleware(['auth', 'verified', 'role:Admin,Manajer Produksi,Staf'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource("criteria", App\Http\Controllers\CriteriaController::class)->middleware(["auth", "role:Admin"]);
Route::prefix("electre/products")->name("electre.product.")->middleware(["auth", "role:Admin,Manajer Produksi"])->group(function () {
    Route::get("/", [App\Http\Controllers\ElectreController::class, "productIndex"])->name("index");
    Route::post("/calculate", [App\Http\Controllers\ElectreController::class, "productCalculate"])->name("calculate");
    Route::get("/history", [App\Http\Controllers\ElectreController::class, "productHistory"])->name("history");
});

Route::prefix("electre/stores")->name("electre.store.")->middleware(["auth", "role:Admin,Manajer Produksi"])->group(function () {
    Route::get("/", [App\Http\Controllers\ElectreController::class, "storeIndex"])->name("index");
    Route::post("/calculate", [App\Http\Controllers\ElectreController::class, "storeCalculate"])->name("calculate");
    Route::get("/history", [App\Http\Controllers\ElectreController::class, "storeHistory"])->name("history");
});
Route::prefix("eoq")->name("eoq.")->middleware(["auth", "role:Admin,Manajer Produksi"])->group(function () {
    Route::get('/', [App\Http\Controllers\EoqController::class, 'index'])->name('index');
    Route::get('/calculator/{rawMaterial}', [App\Http\Controllers\EoqController::class, 'showCalculator'])->name('calculator');
    Route::post('/calculate/{rawMaterial}', [App\Http\Controllers\EoqController::class, 'calculate'])->name('calculate');
    Route::get('/history', [App\Http\Controllers\EoqController::class, 'history'])->name('history');
});
Route::prefix("production-schedule")->name("production-schedule.")->middleware(["auth", "role:Admin,Manajer Produksi"])->group(function () {
    Route::get('/', [App\Http\Controllers\ProductionScheduleController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ProductionScheduleController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\ProductionScheduleController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\ProductionScheduleController::class, 'show'])->name('show');
    Route::post('/{id}/calculate', [App\Http\Controllers\ProductionScheduleController::class, 'calculate'])->name('calculate');
    Route::get('/{id}/result', [App\Http\Controllers\ProductionScheduleController::class, 'result'])->name('result');
    Route::get('/history/all', [App\Http\Controllers\ProductionScheduleController::class, 'history'])->name('history');
    Route::delete('/{id}', [App\Http\Controllers\ProductionScheduleController::class, 'destroy'])->name('destroy');
});
Route::prefix("reports")->name("reports.")->middleware(["auth", "role:Admin,Manajer Produksi,Staf"])->group(function () {
    Route::get("/eoq/history/pdf", [App\Http\Controllers\ReportController::class, "eoqHistoryPdf"])->name("eoq.history.pdf");
    Route::get("/electre/product/history/pdf", [App\Http\Controllers\ReportController::class, "electreProductHistoryPdf"])->name("electre.product.history.pdf");
    Route::get("/electre/store/history/pdf", [App\Http\Controllers\ReportController::class, "electreStoreHistoryPdf"])->name("electre.store.history.pdf");
    Route::get('jadwal-produksi', [App\Http\Controllers\ReportController::class, 'jadwalProduksi'])->name('jadwal-produksi');
    Route::get('jadwal-produksi-pdf', [App\Http\Controllers\ReportController::class, 'jadwalProduksiPdf'])->name('jadwal-produksi.pdf'); 
});
// Product Management Routes
Route::resource('products', App\Http\Controllers\ProductController::class)->middleware(['auth', 'role:Admin']);
Route::resource('raw-materials', App\Http\Controllers\RawMaterialController::class)->middleware(['auth', 'role:Admin']);
Route::resource('stores', App\Http\Controllers\StoreController::class)->middleware(['auth', 'role:Admin']);

// Setting Costs Routes
Route::get('setting-costs', [App\Http\Controllers\SettingCostController::class, 'index'])->name('setting-costs.index')->middleware(['auth', 'role:Admin']);
Route::post('setting-costs', [App\Http\Controllers\SettingCostController::class, 'update'])->name('setting-costs.update')->middleware(['auth', 'role:Admin']);

// Product Score Routes
Route::get('products/{product}/scores', [App\Http\Controllers\ProductScoreController::class, 'edit'])->name('products.scores.edit')->middleware(['auth', 'role:Admin']);
Route::post('products/{product}/scores', [App\Http\Controllers\ProductScoreController::class, 'update'])->name('products.scores.update')->middleware(['auth', 'role:Admin']);

// Store Score Routes
Route::get('stores/{store}/scores', [App\Http\Controllers\StoreScoreController::class, 'edit'])->name('stores.scores.edit')->middleware(['auth', 'role:Admin']);
Route::post('stores/{store}/scores', [App\Http\Controllers\StoreScoreController::class, 'update'])->name('stores.scores.update')->middleware(['auth', 'role:Admin']);

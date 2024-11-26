<?php

use App\Models\Sklad_in\FakturaInput;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sklad\TypeController;
use App\Http\Controllers\Sklad\BrandController;
use App\Http\Controllers\Sklad\MadelController;
use App\Http\Controllers\Postavshik\ConsignorController;
use App\Http\Controllers\Sklad_in\FakturaInputController;
use App\Http\Controllers\Sklad_in\FakturaTovarController;
use App\Http\Controllers\Sales\ClientController;
use App\Http\Controllers\Sales\SaleController;
use App\Http\Controllers\Sklad_out\ServiseController;
use App\Http\Controllers\Sklad_out\SalesTovarController;
use App\Http\Controllers\Kassa\KunlikHisobotController;
use App\Http\Controllers\Kassa\CostController;
use App\Http\Controllers\Kassa\CostTypeController;
use App\Http\Controllers\Sklad_out\PaymentController;
use App\Http\Controllers\Sklad_in\AstatkaTovarController;
use App\Http\Controllers\Postavshik\ConsignorDebetController;
use App\Http\Controllers\Sklad_out\SalesDebetController;
use App\Http\Controllers\Kassa\HisobotController;
use App\Http\Controllers\Investor\InvestorController;
use App\Http\Controllers\Investor\InvestorInputController;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('main');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('types', TypeController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('madels', MadelController::class);
    Route::resource('consignors', ConsignorController::class);
    Route::resource('fakturas', FakturaInputController::class);
    Route::resource('faktura_tovars', FakturaTovarController::class);
    Route::post('/close-faktura/{id}', [FakturaTovarController::class, 'close_faktura'])->name('close_faktura');
    Route::resource('clients', ClientController::class);
    Route::resource('sales', SaleController::class);
    Route::post('/next-unik', [SaleController::class, 'nextUnik'])->name('unik.next');
    Route::resource('servises', ServiseController::class);
    Route::put('/sales_tovars/update_sale', [SalesTovarController::class, 'update_sale'])->name('sales_tovars.update_sale');
    Route::resource('sales_tovars', SalesTovarController::class);
    Route::resource('kunlik_hisobot', KunlikHisobotController::class);
    Route::post('/madels/update-sotuvnarx', [MadelController::class, 'updateSotuvNarx'])->name('madels.update_sotuvnarx');
    Route::resource('costs', CostController::class);
    Route::resource('cost_types', CostTypeController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('astatka_tovars', AstatkaTovarController::class);
    Route::resource('consignor_debets', ConsignorDebetController::class);
    Route::resource('sales_debets', SalesDebetController::class);
    Route::get('/hisobot/view', [HisobotController::class, 'index'])->name('hisobot.index');
    Route::resource('investors', InvestorController::class);
    Route::resource('investor_inputs', InvestorInputController::class);


});

require __DIR__.'/auth.php';

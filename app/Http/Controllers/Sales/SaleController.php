<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sklad_in\FakturaTovar;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sklad\Madel;
use App\Models\Unik;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\StoreSaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\FunctionsServices;



class SaleController extends Controller
{
    protected $functionsServices;

    // Dependency Injection yordamida FunctionsService-ni controllerga o'tkazish
    public function __construct(FunctionsServices $functionsServices)
    {
        $this->functionsServices = $functionsServices;
    }
    
    public function index()
    {
        $unik = Unik::latest('id')->value('id');
        // Barcha astatkada mavjud tovarlar servisdagi funksiya orqali olinyapti
        $items = $this->functionsServices->getAstatkaProducts();
        return view('sales/sale', $this->functionsServices->getSales($items, 0, $unik));
    }

    public function create(){
        $unik = new Unik();
        $unik->save();
    }
    
    public function nextUnik(Request $request)
    {
        $unik = new Unik();
        $unik->save();
        // Javobni qaytarish
        return response()->json([
            'success' => true,
            'message' => 'Yangi Unik yaratildi yoki yangilandi!',
        ]);
    }
    
    public function store(StoreSaleRequest $request)
    {
         $items = FakturaTovar::whereNull('faktura_tovars.deleted_at')
                                    ->where('faktura_tovars.madel_id', $request->tovar_id)
                                    ->join('madels', 'faktura_tovars.madel_id', '=', 'madels.id') // jadvalni birlashtirish
                                    ->orderBy('faktura_tovars.created_at', 'asc') // eng birinchi yozuvni olish
                                    ->select('faktura_tovars.soni', 'faktura_tovars.kirim_narx', 'madels.sotuv_narx') // kerakli ustunlarni tanlash
                                    ->first();

         if($items){
             $farq = $items->sotuv_narx - $items->kirim_narx;
                if($request->chegirma > 10000){
                    $message = "Xatolik! Ruxsat etilgan chegirma ".number_format($farq);
                }
                elseif($request->tovar_soni - $request->soni < 0){
                    $message = "Xatolik! Omborda siz tanlagan tovardan ".$items->soni." dona mavjud xolos!";
                }
                else{
                    $unik = Unik::latest('id')->value('id');

                    SalesTovar::create([
                        'unik_id' => $unik,
                        'madel_id' => $request->tovar_id,
                        'soni' => $request->soni,
                        'sotuv_narx' => $items->sotuv_narx,
                        'chegirma' => $request->chegirma,
                        'chiqim_narx' => $items->sotuv_narx-$request->chegirma,
                        'created_by' => Auth::id(),
                    ]);
                    $message = $request->tovar." xarid savatiga qo'shildi!";

                }
         }
         else $message = "Tovar kirim jadvalidan topilmadi!";
        $unik = Unik::latest('id')->value('id');    
        return $this->functionsServices->getSales($message, 'json', $unik);
    }

    
    public function update(Request $request, string $id)
    {
        $fakturaId = $request->faktura_id;
        
        DB::table('sales_tovars')
            ->where('id', $id)
            ->update([
                'deleted_by' => auth()->user()->id,
                'delete_reason' => 'xatolik',
                'deleted_at' => now(), // yoki Carbon::now() dan foydalanishingiz mumkin
            ]);
        $unik = Unik::latest('id')->value('id');
        return $this->functionsServices->getSales('Tovar o\'chirildi!', 'json', $unik);
    }

}

<?php

namespace App\Http\Controllers\Kassa;

use App\Models\Sklad_out\Servise;
use App\Models\Valyuta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kassa\StoreKunlikHisobotRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sklad_in\FakturaTovar;
use App\Models\Kassa\Cost;
use Illuminate\Support\Carbon;


class KunlikHisobotController extends Controller
{
    public function index()
    {
        // Blade fayliga yuborish
        return view('kassa.kunlik_hisobot');
    }

    public function create(StoreKunlikHisobotRequest $request)
    {
    }

    public function store(StoreKunlikHisobotRequest $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        
       

        $items = SalesTovar::select('madel_id', 'soni', 'chiqim_narx', 'created_at')
                ->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $date1) // $date1 - boshlang'ich sana
                ->whereDate('created_at', '<=', $date2) // $date2 - tugash sanasi
                ->get()
                ->groupBy(function ($item) {
                    // `created_at` ustunini "Y-m-d" formatida guruhlab olish
                    return Carbon::parse($item->created_at)->format('Y-m-d');
                })
                ->map(function ($group, $date) use ($date1, $date2) {
                    $dailySavdoSum = 0;
                    $dailyTannarSum = 0;
            
                    foreach ($group as $item) {
                        $madel_id = $item->madel_id;
                        $created_at = $item->created_at;
            
                        // Savdo summasini hisoblash
                        $dailySavdoSum += $item->soni * $item->chiqim_narx;
            
                        // Faktura tovaridan eng oxirgi kirim narxni olish
                        $fakturaTovarId = FakturaTovar::where('created_at', '<', $created_at)
                            ->where('madel_id', $madel_id)
                            ->orderBy('id', 'desc')
                            ->value('id');
            
                        $tannarSum = $item->soni * (FakturaTovar::where('id', $fakturaTovarId)->value('kirim_narx') ?? 0);
                        $dailyTannarSum += $tannarSum;
                    }
            
                    // Xarajat summasini olish
                    $xarajatSumma = Cost::whereDate('created_at', $date) // Faqat o'sha kun uchun xarajat summasini olish
                        ->whereNull('deleted_at')
                        ->where('cost_type_id', '!=', 1)
                        ->sum('summa');
            
                    // Foydani hisoblash
                    $foyda = $dailySavdoSum - $xarajatSumma;
            
                    return [
                        'sana' => $date,
                        'savdo_summa' => $dailySavdoSum,
                        'xarajat_summa' => $xarajatSumma,
                        'foyda' => $foyda,
                        'tannarx_summa' => $dailyTannarSum,
                    ];
                });
            
            // return $items->values(); 
                         
            //  dd($items);
    
        // 1-so'rov: Hisobot uchun kirim va chiqim
        $hisobot = DB::table('sales as s')
            ->selectRaw("
                SUM(s.naqd) AS naqd,
                SUM(s.plastik) AS plastik,
                SUM(s.nasiya) AS nasiya,
                v.valyuta,
                s.valyuta_kurs,
                DATE(s.created_at) as sana
            ")
            ->join('sales_tovars as st', 's.unik', '=', 'st.unik_id')
            ->join('valyutas as v', 's.valyuta_id', '=', 'v.id')
            ->whereNull('s.deleted_at')
            ->whereNull('st.deleted_at')
            ->whereDate('st.created_at', '>=', $date1)
            ->whereDate('st.created_at', '<=', $date2)
            ->groupBy(
                DB::raw('DATE(s.created_at)'),
                's.valyuta_id'
            )
            ->get();


        return response()->json(['hisobot' => $hisobot, 'chiqimHisobot' => $items->values()]);
    }


    public function show(Servise $servise)
    {
        //
    }

    public function edit(Servise $servise)
    {
        //
    }

    public function update(Request $request, Servise $servise)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan Servise ma'lumotlarini yangilash
        $servise->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('servises.index')->with('success', 'Servis muvaffaqiyatli o\'chirildi.');
    }

    public function destroy(Request $request, Servise $servise) {}
}

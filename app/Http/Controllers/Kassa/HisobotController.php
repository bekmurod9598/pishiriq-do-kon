<?php

namespace App\Http\Controllers\Kassa;

use App\Models\Kassa\Cost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kassa\CostType;
use App\Models\Valyuta;
use App\Models\Postavshik\Consignor;
use Illuminate\Support\Facades\DB;


class HisobotController extends Controller
{
    public function index()
    {
        // Savdolarni yig‘indisini olish
        $sales = DB::table('sales')
            ->select(
                DB::raw('SUM(naqd) as naqd'),
                DB::raw('SUM(plastik) as plastik'),
                DB::raw('SUM(nasiya) as nasiya')
            )
            ->whereNull('deleted_at')
            ->first();
        
        // To‘lovlarni yig‘indisini olish
        $payments = DB::table('payments')
            ->select(
                DB::raw('SUM(naqd) as naqd'),
                DB::raw('SUM(plastik) as plastik')
            )
            ->whereNull('deleted_at')
            ->first();
        
        // Investor kirimlarini yig‘indisini olish
        $investorInputs = DB::table('investor_inputs')
            ->select(
                DB::raw('SUM(naqd) as naqd'),
                DB::raw('SUM(plastik) as plastik')
            )
            ->whereNull('deleted_at')
            ->first();
        
        // Xarajatlarni hisoblash
        $xarajat_summa = DB::table('costs')
            ->whereNull('deleted_at')
            ->sum('summa');
        
        // Kassadagi umumiy farqni hisoblash
        $kassa_naqd = $sales->naqd + $payments->naqd + $investorInputs->naqd;
        $kassa_plastik = $sales->plastik + $payments->plastik + $investorInputs->plastik;
        $kassa_farq = $kassa_naqd + $kassa_plastik - $xarajat_summa;
        
        // Natijani viewga yuborish
        return view('kassa.hisobot', [
            'naqd' => $sales->naqd,
            'plastik' => $sales->plastik,
            'nasiya' => $sales->nasiya,
            'tolov' => $payments->naqd + $payments->plastik,
            'investor_summa' => $investorInputs->naqd + $investorInputs->plastik,
            'xarajat' => $xarajat_summa,
            'farq' => $kassa_farq,
        ]);
    }

}

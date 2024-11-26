<?php

namespace App\Http\Controllers\Sklad_out;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sklad_out\FakturaTovar;
use App\Http\Requests\Sklad_out\StoreSalesTovarRequest;
use App\Models\Valyuta;
use App\Models\Unik;
use App\Models\Sklad\Madel;
use App\Models\Sales\Client;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sales\Sale;
use App\Models\Extra\Valyuta_kurs;
use Illuminate\Support\Facades\DB;
use App\Services\FunctionsServices;
use Carbon\Carbon;


class SalesDebetController extends Controller
{
    protected $functionsServices;

    // Dependency Injection yordamida FunctionsService-ni controllerga o'tkazish
    public function __construct(FunctionsServices $functionsServices)
    {
        $this->functionsServices = $functionsServices;
    }
    
    public function index()
    {
       $uniklar = Unik::select(
        'uniks.id', 
        'uniks.status', 
        'clients.client', 
        'clients.phone', 
        'valyutas.valyuta', 
        'sales.id as sales_id', 
        'sales.paid_at', 
        'sales.naqd', 
        'sales.plastik', 
        'sales.nasiya',
         DB::raw('COALESCE(payments_sum.summa, 0) as summa')
    )
    ->join('sales', 'uniks.id', '=', 'sales.unik')
    ->join('clients', 'sales.client_id', '=', 'clients.id')
    ->join('valyutas', 'sales.valyuta_id', '=', 'valyutas.id')
    ->leftJoin(DB::raw('(SELECT unik_id, SUM(COALESCE(naqd, 0) + COALESCE(plastik, 0)) as summa FROM payments WHERE deleted_at IS NULL GROUP BY unik_id) as payments_sum'), 'uniks.id', '=', 'payments_sum.unik_id')
    ->whereNull('sales.deleted_at')
    ->whereNull('sales.closed_at')
    ->whereNotNull('sales.paid_at')
    ->orderBy('uniks.id', 'ASC')
    ->get()
    ->map(function($unik) {
        $summa = $unik->summa ?? 0; // Agarda `summa` qiymati bo'sh bo'lsa, 0 qiymatni qo'llash
        $klass = Carbon::parse($unik->paid_at)->isPast() ? "table-danger" : "";

        return [
            'id' => $unik->id,
            'sales_id' => $unik->sales_id,
            'status' => $unik->status,
            'client' => $unik->client,
            'phone' => $this->functionsServices->formatPhoneNumber($unik->phone),
            'valyuta' => $unik->valyuta,
            'naqd' => $unik->naqd,
            'plastik' => $unik->plastik,
            'nasiya' => $unik->nasiya,
            'paid' => $summa,
            'debet' => $unik->nasiya - $summa,
            'jami' => ($unik->naqd + $unik->plastik + $unik->nasiya),
            'vaqt' => Carbon::parse($unik->paid_at)->format("d.m.Y"),
            'klass' => $klass
        ];
    });

// 'uniklar' o'zgaruvchisini view ga yuborish
return view('sklad_out.sales_debet', ['uniks' => $uniklar]);
    }
    
}

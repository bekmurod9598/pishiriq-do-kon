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


class SalesTovarController extends Controller
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
                DB::raw('(SELECT MAX(st2.created_at) FROM sales_tovars AS st2 WHERE st2.unik_id = uniks.id AND st2.deleted_at IS NULL) AS created_at'),
                'clients.client', 
                'valyutas.valyuta', 
                'sales.id as sales_id', 
                'sales.naqd', 
                'sales.plastik', 
                'sales.nasiya',
                'sales.chegirma'
            )
            ->join('sales_tovars as st', 'uniks.id', '=', 'st.unik_id')
            ->leftJoin('sales', 'uniks.id', '=', 'sales.unik')
            ->leftJoin('clients', 'sales.client_id', '=', 'clients.id')
            ->leftJoin('valyutas', 'sales.valyuta_id', '=', 'valyutas.id')
            ->whereNull('st.deleted_at')
            ->whereNull('sales.deleted_at')
            ->groupBy('uniks.id', 'uniks.status', 'clients.client', 'valyutas.valyuta', 'sales.id', 'sales.naqd', 'sales.plastik', 'sales.nasiya')
            ->orderBy('uniks.id', 'DESC')
            ->distinct()
            ->get()
            ->map(function($unik) {
                
                return [
                    'id' => $unik->id,
                    'sales_id' => $unik->sales_id,
                    'status' => $unik->status,
                    'client' => $unik->client,
                    'valyuta' => $unik->valyuta,
                    'naqd' => $unik->naqd,
                    'plastik' => $unik->plastik,
                    'nasiya' => $unik->nasiya,
                    'chegirma' => $unik->chegirma,
                    'jami' => ($unik->naqd+$unik->plastik+$unik->nasiya),
                    'vaqt' => $unik->created_at,
                ];
            });

        // 'uniklar' o'zgaruvchisini view ga yuborish
        return view('sklad_out.sales_tovar', ['uniks' => $uniklar]);
    }
    
    public function show(string $unik){
        return $this->functionsServices->getSales('Tovar ochildi!', 'json', $unik);
    }
    
    public function edit(string $id)
    {
        // Savdo summasini olish
        $summa = DB::table('sales_tovars')
                ->where('unik_id', $id)
                ->whereNull('deleted_at')
                ->select(DB::raw('SUM(soni * chiqim_narx) as summa'))
                ->value('summa');

        $clients = Client::all();
        $valyutas = Valyuta::all();

        if (!$clients || !$valyutas) {
            return response()->json(['error' => 'Mijoz yoki valyuta topilmadi'], 404);
        }

        // Barcha ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'clients' => $clients,
            'valyutas' => $valyutas,
            'summa' => $summa
        ]);
    }

    public function store(StoreSalesTovarRequest $request)
    {
        try {
            $chgirma_limit = $request->hidden_jami * 0.1;
            if($chgirma_limit < 1*$request->chegirma)
                return response()->json(['errors' => ['chegirma' => 'Ruxsat etilgan maksimal chegirma '.number_format($chgirma_limit, '2', ',', ' ')]], 423);

            elseif(($request->naqd + $request->plastik + $request->nasiya + $request->chegirma) != $request->hidden_jami)
                return response()->json(['errors' => 'Savdo summasini hisoblashda xato!'], 424);
            else {
                $kurs = Valyuta_kurs::latest('id')->value('kurs');
                $sales = Sale::create(
                    [
                        'client_id' => $request->client, 
                        'valyuta_id' => $request->valyuta, 
                        'unik' => $request->unik,
                        'naqd' => $request->naqd, 
                        'plastik' => $request->plastik, 
                        'nasiya' => $request->nasiya,
                        'chegirma' => $request->chegirma,
                        'valyuta_kurs' => $kurs, 
                        'paid_at' => $request->nasiya_sanasi,
                        'created_by' => $request->user_id, 
                    ]
                );
                Unik::where('id', $request->unik)
                            ->update([
                                'status' => 0, 
                                'updated_at' => now()
                            ]);
                 
                //sms yuborish
                if($request->nasiya>0)
                    $sms_text = 'Hurmatli mijoz! "Yasin materials" dan '.$request->naqd.' so\'mlik naqd, '.$request->nasiya.' so\'mlik keyin to\'lashga jami '.($request->naqd+$request->nasiya).'so\'mlik maxsulot xarid qildingiz. Savdo raqami:'.$request->unik;
                else
                    $sms_text = 'Hurmatli mijoz! "Yasin materials" dan naqd pulga '.$request->naqd.' so\'mlik maxsulot xarid qildingiz. Savdo raqami:'.$request->unik;
                // $this->functionsServices->sendSms('998901921515', 'salom bu test');
                // Agar muvaffaqiyatli saqlansa, JSON formatida muvaffaqiyat xabarini qaytarish
                return response()->json(['success' => true, 'message' => 'Ma\'lumotlar muvaffaqiyatli saqlandi!']);
            }
        } 
        catch (\Illuminate\Validation\ValidationException $e) {
            // Agar validatsiyada xatolik bo'lsa, xatoliklarni qaytarish
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function update_sale(Request $request)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|min:5|max:255',
        ]);
        $unik = $request->unik_id_field;

        $sale = Sale::where('unik', $unik)->first();

        // Agar yozuv topilgan bo'lsa, yangilash (soft delete uchun)
        if ($sale) {
            $sale->update([
                'deleted_at' => now(),
                'deleted_by' => $request->user_id,
                'delete_reason' => $request->reason,
            ]);
        }
        $saleTovars = SalesTovar::where('unik_id', $unik)->get();

        // Agar yozuvlar topilgan bo'lsa, har birini yangilang (soft delete uchun)
        foreach ($saleTovars as $saleTovar) {
            $saleTovar->update([
                'deleted_at' => now(),
                'deleted_by' => $request->user_id,
                'delete_reason' => $request->reason,
            ]);
        }
        
        //unikni bitta oshirib qo'yish
        $unik = new Unik();
        $unik->save();
        
        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->back()->with('success', 'Savdo muvaffaqiyatli o\'chirib tashlandi!');

    }
    
}

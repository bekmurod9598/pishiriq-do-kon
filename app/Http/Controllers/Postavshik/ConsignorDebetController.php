<?php

namespace App\Http\Controllers\Postavshik;

use App\Models\Postavshik\Consignor;
use App\Models\Sklad_in\FakturaInput;
use App\Models\Valyuta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Postavshik\StoreConsignorRequest;
use Illuminate\Support\Facades\DB;


class ConsignorDebetController extends Controller
{
    public function index()
    {
        // 1. Tolov summasini konsignor_id bo'yicha olish
        $tolov = DB::table('costs')
            ->select('consignor_id', DB::raw('SUM(summa) as summa'))
            ->where('cost_type_id', 1)
            ->whereNull('deleted_at')
            ->groupBy('consignor_id')
            ->get()
            ->keyBy('consignor_id');
    
        // 2. Fakturalarni olish va har birini qayta ishlash
        $fakturas = FakturaInput::whereNull('deleted_at')
            ->whereNotNull('closed_at')
            ->orderBy('consignor_id')
            ->get()
            ->map(function ($item) use ($tolov) {
                $faktura = $item->faktura;
                $consignor_name = $item->consignor->consignor ?? null;
                $item_summa = $item->summa;
                $valyuta_id = $item->valyuta_id;
                $sana = $item->created_at;
    
                // Valyutani topish
                $valyuta = Valyuta::find($valyuta_id);
                $valyuta_nomi = $valyuta ? $valyuta->valyuta : 'Noma’lum';
    
                // Tolovda mavjudmi tekshirish
                if ($tolov->has($item->consignor_id)) {
                    $tolov_summa = $tolov[$item->consignor_id]->summa;
    
                    // Agar item summasi to'lov summasidan kichik bo'lsa
                    if ($item_summa < $tolov_summa) {
                        $tolov[$item->consignor_id]->summa -= $item_summa;
                        return null; // Sikl davom ettiriladi
                    }
    
                    // Qarzdorlikni hisoblash
                    $qarz = $item_summa - $tolov_summa;
    
                    return [
                        'id' => $item->id,
                        'faktura' => $faktura,
                        'consignor' => $consignor_name,
                        'qarz' => $qarz,
                        'valyuta' => $valyuta_nomi,
                        'sana' => $sana
                    ];
                }
    
                // Agar to'lov mavjud bo'lmasa, to'liq summani qarz deb qaytaradi
                return [
                    'id' => $item->id,
                    'faktura' => $faktura,
                    'consignor' => $consignor_name,
                    'qarz' => $item_summa,
                    'valyuta' => $valyuta_nomi,
                    'sana' => $sana
                ];
            })
            ->filter(); // Null qiymatlarni olib tashlash
    
        // Natijalarni blade fayliga yuborish
        return view('postavshik.consignor_debet', compact('fakturas'));
    }



    public function store(StoreConsignorRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();

        // Yangi Consignor yaratish
        Consignor::create([
            'consignor' => $validatedData['consignor'],
            'adress' => $validatedData['adress'],
            'phone' => $validatedData['phone'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi postavshik muvaffaqiyatli yaratildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignor $consignor)
    {
        // Ko'rsatiladigan resurs haqida ma'lumot ko'rsatish (agar kerak bo'lsa)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consignor $consignor)
    {
        // Tahrirlash uchun form ko'rsatish (agar kerak bo'lsa)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consignor $consignor)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan Consignor ma'lumotlarini yangilash
        $consignor->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id, // Avtorizatsiyalangan foydalanuvchi ID'si
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('consignors.index')->with('success', 'Postavshik muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Consignor $consignor)
    {
        // Agar kerak bo'lsa, resursni o'chirish qoidalari
        $consignor->delete();

        return redirect()->route('consignors.index')->with('success', 'Consignor muvaffaqiyatli o\'chirildi.');
    }
}

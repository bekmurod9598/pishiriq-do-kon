<?php

namespace App\Http\Controllers\Postavshik;

use App\Models\Postavshik\Consignor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Postavshik\StoreConsignorRequest;

class ConsignorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Barcha Consignorlarni olish
        $consignors = Consignor::all();

        // Blade fayliga yuborish
        return view('postavshik.consignor', compact('consignors')); // 'brands' o'rniga 'consignors'
    }


    /**
     * Store a newly created resource in storage.
     */
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

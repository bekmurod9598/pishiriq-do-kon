<?php

namespace App\Http\Controllers\Sklad_in;

use App\Models\Valyuta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Postavshik\Consignor;
use App\Models\Sklad_in\FakturaInput;
use App\Http\Requests\Sklad_in\StoreFakturaInputRequest;

class FakturaInputController extends Controller
{
    public function index()
    {
        // Barcha tur, brend va madellarni olish
        $consignors = Consignor::all();
        $valyutas = Valyuta::all();
        $fakturas = FakturaInput::with(['consignor', 'valyuta'])->get();

        // Blade fayliga yuborish
        return view('sklad_in.fakturaInput', compact('consignors', 'valyutas', 'fakturas'));
    }

    public function store(StoreFakturaInputRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();

        // Yangi model yaratish
        FakturaInput::create([
            'consignor_id' => $validatedData['consignor'],
            'faktura' => $validatedData['faktura'],
            'valyuta_id' => $validatedData['valyuta'],
            'valyuta_kurs' => $validatedData['kurs'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi faktura muvaffaqiyatli yaratildi!');
    }



    public function update(Request $request, FakturaInput $faktura)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan faktura ma'lumotlarini yangilash
        $faktura->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('fakturas.index')->with('success', 'Faktura muvaffaqiyatli o\'chirildi.');
    }

}

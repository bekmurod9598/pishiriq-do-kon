<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Investor\StoreInvestorRequest;
use App\Models\Investor\Investor;

class InvestorController extends Controller
{

    public function index()
    {
        // Barcha Xarajat turlarni olish
        $investors = Investor::all();

        // Blade fayliga yuborish
        return view('investor.investor', compact('investors'));
    }


    public function store(StoreInvestorRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();
        // Yangi costType yaratish
        Investor::create([
            'investor' => $validatedData['investor'],
            'adress' => $validatedData['adress'],
            'phone' => $validatedData['phone'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi investor muvaffaqiyatli yaratildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Investor $investor)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan CostType ma'lumotlarini yangilash
        $investor->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->user()->id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('investors.index')->with('success', 'Investor muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

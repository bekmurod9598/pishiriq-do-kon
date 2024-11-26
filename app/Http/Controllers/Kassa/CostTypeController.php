<?php

namespace App\Http\Controllers\Kassa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kassa\CostType;
use App\Http\Requests\Kassa\StoreCostTypeRequest;

class CostTypeController extends Controller
{

    public function index()
    {
        // Barcha Xarajat turlarni olish
        $costTypes = CostType::all();

        // Blade fayliga yuborish
        return view('kassa.cost_type', compact('costTypes'));
    }


    public function store(StoreCostTypeRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();
        // Yangi costType yaratish
        CostType::create([
            'type' => $validatedData['type'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi xarajat turi muvaffaqiyatli yaratildi!');
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

    public function update(Request $request, CostType $costType)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan CostType ma'lumotlarini yangilash
        $costType->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->user()->id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('cost_types.index')->with('success', 'Xarajat turi muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Sklad;

use App\Models\Sklad\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sklad\StoreTypeRequest;


class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Barcha typelarni olish
        $types = Type::all();

        // Blade fayliga yuborish
        return view('sklad.type', compact('types'));
    }

    public function create()
    {
        //
    }

    public function store(StoreTypeRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();

        // Yangi type yaratish
        Type::create([
            'type' => $validatedData['type'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi tur muvaffaqiyatli yaratildi!');
    }

    public function show(Type $type)
    {
        //
    }

    public function edit(Type $type)
    {
        //
    }

    public function update(Request $request, Type $type)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan type ma'lumotlarini yangilash
        $type->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('types.index')->with('success', 'Tur muvaffaqiyatli o\'chirildi.');
    }

    public function destroy(Request $request, Type $type)
    {

    }

}

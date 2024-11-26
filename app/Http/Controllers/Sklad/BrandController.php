<?php

namespace App\Http\Controllers\Sklad;

use App\Models\Sklad\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sklad\StoreBrandRequest;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Barcha Brandlarni olish
        $brands = Brand::all();

        // Blade fayliga yuborish
        return view('sklad.brand', compact('brands'));
    }

    public function create()
    {
        //
    }

    public function store(StoreBrandRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();
        // Yangi brand yaratish
        Brand::create([
            'brand' => $validatedData['brand'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi brend muvaffaqiyatli yaratildi!');
    }

    public function show(Brand $brand)
    {
        //
    }

    public function edit(Brand $brand)
    {
        //
    }

    public function update(Request $request, Brand $brand)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan Brand ma'lumotlarini yangilash
        $brand->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('brands.index')->with('success', 'Brend muvaffaqiyatli o\'chirildi.');
    }

    public function destroy(Request $request, Brand $brand) {}
}

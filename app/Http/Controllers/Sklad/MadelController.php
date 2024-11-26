<?php

namespace App\Http\Controllers\Sklad;

use App\Models\Sklad\Madel;
use App\Models\Sklad\Type;
use App\Models\Sklad\Brand;
use App\Models\Valyuta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sklad\StoreMadelRequest;


class MadelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Barcha tur, brend va madellarni olish
        $types = Type::all();
        $brands = Brand::all();
        $valyutas = Valyuta::all();
        $madels = Madel::with(['type', 'brand'])->orderBy('id', 'desc')->get();
        // dd($valyutas);

        // Blade fayliga yuborish
        return view('sklad.madel', compact('madels', 'brands', 'types', 'valyutas'));
    }

    public function create()
    {
        //
    }

    public function store(StoreMadelRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();

        // Yangi model yaratish
        Madel::create([
            'type_id' => $validatedData['type'],
            'brand_id' => $validatedData['brand'],
            'madel' => $validatedData['madel'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi model muvaffaqiyatli yaratildi!');
    }

    public function show(Madel $madel)
    {
        //
    }

    public function edit(Madel $madel)
    {
        //
    }

    public function update(Request $request, Madel $madel)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        // dd($madel);
        // O'chirilayotgan Brand ma'lumotlarini yangilash
        $madel->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('madels.index')->with('success', 'Model muvaffaqiyatli o\'chirildi.');
    }

   // Madelning sotuv narxini yangilash
    public function updateSotuvNarx(Request $request)
    {
        // Kiruvchi ma'lumotlarni tasdiqlash
        $validated = $request->validate([
            'madel_id' => 'required|exists:madels,id',  // ID mavjud bo'lishi kerak
            'sotuv_narx_e' => 'required|string', // Narx raqam bo'lishi kerak, lekin probellar bo'lishi mumkin
        ]);
        // dd($request);
    
        // $id orqali madelni topamiz
        $madel = Madel::findOrFail($request->madel_id);
    
        // Sotuv narxidagi probellarni olib tashlaymiz
        $cleanSotuvNarx = str_replace(' ', '', $request->sotuv_narx_e);
    
        // Tozalangan qiymatni raqam sifatida yangilash
        $madel->sotuv_narx = (float)$cleanSotuvNarx;
    
        $madel->save();
    
        // Yangilanganidan so'ng redirect qilish
        return redirect()->back()->with('success', 'Sotuv narxi muvaffaqiyatli yangilandi!');
    }


}

<?php

namespace App\Http\Controllers\Sklad_in;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Postavshik\Consignor;
use App\Models\Sklad_in\FakturaInput;
use App\Models\Sklad_in\FakturaTovar;
use App\Http\Requests\Sklad_in\StoreFakturaTovarRequest;
use App\Models\Valyuta;
use App\Models\Sklad\Madel; // To'g'ri namespace ni qo'llang
use Illuminate\Support\Facades\DB;


class FakturaTovarController extends Controller
{
    public function index()
    {
        // dd(Madel::get());
        // Barcha fakturalarni olish
        $fakturas = FakturaInput::with(['consignor', 'valyuta'])->orderBy('id', 'desc')->get();

        // Blade fayliga yuborish
        return view('sklad_in.fakturaTovar', compact('fakturas'));
    }
    
    public function show(string $fakturaId){
        $results = DB::table('faktura_tovars as f')
                ->join('madels as m', 'f.madel_id', '=', 'm.id')
                ->join('faktura_inputs as fi', 'f.faktura_id', '=', 'fi.id')
                ->join('users as u', 'f.created_by', '=', 'u.id')
                ->where('fi.id', $fakturaId)
                ->whereNull('f.deleted_at')
                ->select('fi.faktura', 'f.soni', 'f.kirim_narx', 'f.sotuv_narx', 'm.madel', 'f.madel_id', 'u.name', 'f.created_at', 'f.id')
                ->orderByDesc('f.id')  // f.id bo'yicha teskari tartibda tartiblash
                ->get();
        
            // Ma'lumotlarni JSON formatda qaytarish
            return response()->json([
                'faktura_tovars' => $results,
                'faktura_id' => $fakturaId
            ]);
    }
    
    public function edit(string $id)
    {
        // So'rovdan faktura_id ni olish
        $fakturaId = $id;

        // Fakturani faktura_id orqali olish
        $faktura = FakturaInput::find($fakturaId);

        if (!$faktura) {
            return response()->json(['error' => 'Faktura topilmadi'], 404);
        }

        // Madellarni types va brands bilan birga olish
        $madels = Madel::with(['type', 'brand'])->get();

        // Barcha ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'faktura' => $faktura,
            'madels' => $madels
        ]);
    }

    public function store(StoreFakturaTovarRequest $request)
    {
        // Validatsiyadan o'tgan ma'lumotlar array sifatida qaytadi
        $validatedData = $request->validated();
        
        // Faktura tovari yaratish
        $fakturaTovar = FakturaTovar::create([
            'faktura_id' => $validatedData['faktura_id'], // Array usulida ma'lumot olish
            'madel_id' => $validatedData['madel'],     // 'madel_id' deb to'g'rilash
            'soni' => $validatedData['soni'],
            'kirim_narx' => $validatedData['kirim_narx'],
            'sotuv_narx' => $validatedData['sotuv_narx'],
            'created_by' => $validatedData['user_id']
        ]);
        
        //madeldagi sotuvnarxni yangilash
        Madel::where('id', $validatedData['madel'])->update([
            'sotuv_narx' => $validatedData['sotuv_narx'],
            'updated_at' => now(),
        ]);

        $fakturaId = $validatedData['faktura_id'];
        return $this->show($fakturaId);
    }

    public function update(Request $request, string $id)
    {   
        $fakturaId = $request->faktura_id;
        
        DB::table('faktura_tovars')
            ->where('id', $id)
            ->update([
                'deleted_by' => auth()->user()->id,
                'delete_reason' => 'xatolik',
                'deleted_at' => now(), // yoki Carbon::now() dan foydalanishingiz mumkin
            ]);
        
        return $this->show($fakturaId);
    }
    
    public function close_faktura($i)
    {
        // Fakturani ID bo'yicha topish
        $id = $_POST['faktura_id0'];
        $totalKirimNarx = DB::table('faktura_tovars as f')
                            ->where('f.faktura_id', $id)
                            ->whereNull('f.deleted_at')
                            ->sum(DB::raw('f.kirim_narx * f.soni'));

        $fakturaInput = FakturaInput::find($id);

        // Agar faktura mavjud bo'lsa
        if ($fakturaInput) {
            // Statusni yangilash yoki kerakli boshqa o'zgarishlarni qilish
            $fakturaInput->closed_at = now();
            $fakturaInput->summa = $totalKirimNarx;
            $fakturaInput->save();

            // Muvaffaqiyatli xabar bilan qaytarish
            return redirect()->back()->with('success', 'Faktura muvaffaqiyatli yopildi.');
        }

        // Agar faktura topilmasa, xatolik xabari bilan qaytarish
        return redirect()->back()->with('error', 'Faktura topilmadi.');
    }
    
}

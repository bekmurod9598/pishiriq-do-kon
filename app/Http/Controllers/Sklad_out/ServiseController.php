<?php

namespace App\Http\Controllers\Sklad_out;

use App\Models\Sklad_out\Servise;
use App\Models\Valyuta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sklad_out\SalesTovar;
use App\Http\Requests\Sklad_out\StoreServiseRequest;
use Carbon\Carbon;


class ServiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Tashqi hisoblagich o'zgaruvchi
        $i = 0;

        // Joriy yil va oyni olish
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // SalesTovar ma'lumotlarini so'rov qilish va kerakli shartlarni qo'shish
        $items = SalesTovar::whereNull('deleted_at')
            ->whereYear('created_at', $currentYear) // Joriy yil bo'yicha filtr
            ->whereMonth('created_at', $currentMonth) // Joriy oy bo'yicha filtr
            ->whereIn('madel_id', [67, 68]) // madel_id 67 yoki 68 bo'lganlar
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d'); // created_at bo'yicha kunlarga guruhlash
            })
            ->map(function($dayGroup) use (&$i) {
                return $dayGroup->groupBy('madel_id')->map(function($group) use (&$i) {
                    $totalSoni = $group->sum('soni');
                    $totalSumma = $group->sum(function ($item) {
                        return $item->soni * $item->chiqim_narx;
                    });
        
                    $firstItem = $group->first();
                    $sana = date("d.m.Y", strtotime($firstItem->created_at));
        
                    // Agar $firstItem mavjud bo'lmasa, null qaytaradi
                    if (!$firstItem) {
                        return null;
                    }
        
                    // Ketma-ket tartibni oshirish
                    $i++;
        
                    return [
                        'i' => $i, // Ketma-ket tartibda 'i' qiymati
                        'sana' => $sana,
                        'tname' => ($firstItem->Madel && $firstItem->Madel->Type && $firstItem->Madel->Brand) ? 
                            $firstItem->Madel->id . ". ". $firstItem->Madel->Type->type . " " . $firstItem->Madel->Brand->brand . " " . $firstItem->Madel->madel : 
                            'Ma\'lumot yetarli emas',
                        'soni' => $totalSoni,
                        'chiqim_narx' => $firstItem->chiqim_narx,
                        'chegirma' => $firstItem->chegirma * $totalSoni,
                        'summa' => $totalSumma,
                    ];
                })->filter(); // null qiymatlarni olib tashlash
            })->filter() // Bo'sh kunlarni olib tashlash
            ->reverse(); // Oxirgi teskari tartibga keltirish
        
        // Blade fayliga yuborish
        return view('sklad_out.servise', compact('items'));

    }

    public function create()
    {
        //
    }

    public function store(StoreServiseRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();
        // Yangi servise yaratish
        Servise::create([
            'nomi' => $validatedData['nomi'],
            'narxi' => $validatedData['narxi'],
            'valyuta_id' => $validatedData['valyuta'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi Servis muvaffaqiyatli yaratildi!');
    }

    public function show(Servise $servise)
    {
        //
    }

    public function edit(Servise $servise)
    {
        //
    }

    public function update(Request $request, Servise $servise)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan Servise ma'lumotlarini yangilash
        $servise->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('servises.index')->with('success', 'Servis muvaffaqiyatli o\'chirildi.');
    }

    public function destroy(Request $request, Servise $servise) {}
}

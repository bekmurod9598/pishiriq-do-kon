<?php

namespace App\Http\Controllers\Investor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\StoreInvestorInputRequest;
use App\Models\Valyuta;
use App\Models\Extra\Valyuta_kurs;
use App\Models\Investor\Investor;
use App\Models\Investor\InvestorInput;
use Illuminate\Support\Facades\DB;


class InvestorInputController extends Controller
{
    public function index()
    {
        $investorInputs = InvestorInput::get()
                        ->map(function($item) {
                            $valyuta = Valyuta::find($item->valyuta_id);
                            $model = InvestorInput::find($item->id);
                            return[
                                'id' => $item->id,
                                'item' => $model,
                                'investor' => $item->investor->investor,
                                'valyuta' => $valyuta->valyuta,
                                'naqd' => $item->naqd,
                                'plastik' => $item->plastik,
                                'izoh' => $item->izoh,
                                'vaqt' => $item->created_at
                                ];
                        });
        
        $investors = Investor::all();
        $valyutas = Valyuta::all();
        
        // Blade fayliga yuborish
        return view('investor.investor_input', [
            'investor_inputs' => $investorInputs,
            'investors' => $investors,
            'valyutas' => $valyutas,
            'message' => 'Ma\'lumotlar muvaffaqiyatli yuklandi.'
        ]);
        
        
    }


    public function store(StoreInvestorInputRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatlarni olish
        $validatedData = $request->validated();
    
        // Valyuta kursini olish
        $kurs = Valyuta_kurs::latest('id')->value('kurs');
        
        // Yangi kirim yozuvini yaratish 
        InvestorInput::create([
            'investor_id' => $validatedData['investor'], // 'investor' ni to'g'ri ishlatish kerak
            'valyuta_id' => $validatedData['valyuta'],
            'naqd' => $validatedData['naqd'],
            'plastik' => $validatedData['plastik'],
            'valyuta_kurs' => $kurs,
            'izoh' => $validatedData['izoh'],
            'created_by' => auth()->user()->id,
        ]);
    
        // Muvaffaqiyatli javob qaytarish
        return redirect()->route('investor_inputs.index')->with('success', "Kirim muvaffaqiyatli qo'shildi");
    }

    public function update(Request $request, InvestorInput $input)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        // dd($input);
        // O'chirilayotgan cost ma'lumotlarini yangilash
        $input->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->user()->id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('investor_inputs.index')->with('success', 'Kirim muvaffaqiyatli o\'chirildi.');
    }

}

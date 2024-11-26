<?php

namespace App\Http\Controllers\Kassa;

use App\Models\Kassa\Cost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kassa\StoreCostRequest;
use App\Models\Kassa\CostType;
use App\Models\Valyuta;
use App\Models\Postavshik\Consignor;
use Illuminate\Support\Facades\DB;


class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Barcha Costlarni olish

    $costs = DB::table('costs as c')
                        ->join('cost_types as ct', 'c.cost_type_id', '=', 'ct.id')
                        ->leftjoin('consignors as con', 'c.consignor_id', '=', 'con.id')
                        ->join('valyutas as v', 'c.valyuta_id', '=', 'v.id')
                        ->whereNull('c.deleted_at')
                        ->orderByDesc('c.id')
                        ->limit(1000)
                        ->select([
                            'c.id',
                            'ct.type', 
                            'con.consignor', 
                            'v.valyuta', 
                            'c.summa', 
                            'c.pay_type', 
                            'c.izoh', 
                            'c.created_at'
                        ])
                        ->get();
    

        // Blade fayliga yuborish
        return view('kassa.cost', [
            'costs' => $costs,
            'message' => 'Ma\'lumotlar muvaffaqiyatli yuklandi.'
        ]);
        
        
    }

    public function create()
    {
        // dd('$types');
        $types = CostType::all();
        $valyutas = Valyuta::all();
        $consignors = Consignor::all();

        if (!$types || !$valyutas) {
            return response()->json(['error' => 'Xarajat turi yoki valyuta topilmadi'], 404);
        }

        // Barcha ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'types' => $types,
            'valyutas' => $valyutas,
            'consignors' => $consignors
        ]);
    }

        public function store(StoreCostRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatlarni olish
        $validatedData = $request->validated();
        
        $postavshik_id = $validatedData['type']==1 ? $validatedData['postavshik'] : null;
        // Yangi xarajat yozuvini yaratish `cost_type_id`, `summa`, `izoh`, `created_by`
        Cost::create([
            'cost_type_id' => $validatedData['type'],
            'consignor_id' => $postavshik_id,
            'valyuta_id' => $validatedData['valyuta'],
            'summa' => $validatedData['summa'],
            'pay_type' => $validatedData['pay_type'],
            'izoh' => $validatedData['izoh'],
            'created_by' => $validatedData['user_id'],
        ]);
    
        // Muvaffaqiyatli javob qaytarish
        session()->flash('success', 'Yangi xarajat muvaffaqiyatli yaratildi!');
        return response()->json([
            'success' => true,
            'message' => 'Yangi xarajat muvaffaqiyatli yaratildi!'
        ]);

    }

    public function show(Cost $cost)
    {
        //
    }

    public function edit(Cost $cost)
    {
        //
    }

    public function update(Request $request, Cost $cost)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan cost ma'lumotlarini yangilash
        $cost->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->user()->id,
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('costs.index')->with('success', 'Xarajat muvaffaqiyatli o\'chirildi.');
    }

    public function destroy(Request $request, Cost $cost) {}
}

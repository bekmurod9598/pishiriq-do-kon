<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\StoreClientRequest;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        // Barcha Clientlarni olish
        $clients = DB::table('clients as c')
                    ->join('branches as b', 'c.branch_id', '=', 'b.id')
                    ->whereNull('c.deleted_at')
                    ->select('c.id', 'c.client', 'c.phone', 'c.phone_extra', 'c.adress', 'c.created_at', 'b.branch')
                    ->get();

        // Blade fayliga yuborish
        return view('sales.client', compact('clients')); // 'brands' o'rniga 'clients'
    }


    public function store(StoreClientRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatni olish
        $validatedData = $request->validated();
        // dd($validatedData);
        // Yangi Client yaratish
        Client::create([
            'client' => $validatedData['client'],
            'adress' => $validatedData['adress'],
            'phone' => $validatedData['phone'],
            'phone_extra' => $validatedData['phone_extra'],
            'created_by' => $validatedData['user_id'],
        ]);

        // Muvaffaqiyatli yaratildi xabari bilan qaytarish
        return redirect()->back()->with('success', 'Yangi mijoz muvaffaqiyatli yaratildi!');
    }

    public function edit(Client $client)
    {
        // Tahrirlash uchun form ko'rsatish (agar kerak bo'lsa)
    }

    public function update(Request $request, Client $client)
    {
        // Validatsiya qoidalari
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // O'chirilayotgan Client ma'lumotlarini yangilash
        $client->update([
            'deleted_at' => now(),
            'deleted_by' => $request->user_id, // Avtorizatsiyalangan foydalanuvchi ID'si
            'delete_reason' => $request->reason,
        ]);

        // Muvaffaqiyatli o'chirilgani haqida xabar
        return redirect()->route('clients.index')->with('success', 'Mijoz muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Client $client)
    {
        // Agar kerak bo'lsa, resursni o'chirish qoidalari
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client muvaffaqiyatli o\'chirildi.');
    }
}

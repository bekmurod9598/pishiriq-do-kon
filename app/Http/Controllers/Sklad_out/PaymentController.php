<?php

namespace App\Http\Controllers\Sklad_out;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sklad_out\StorePaymentRequest;
use App\Models\Sklad_out\Payment;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sales\Sale;
use App\Models\Sales\Client;
use App\Models\Valyuta;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        $valyutas = Valyuta::all();
        return view('sklad_out.payment', compact('clients', 'valyutas'));
    }

    public function create()
    {
        // dd('$types');
        //`sale_id`, `client_id`, `valyuta_id`, `naqd`, `plastik`, `valyuta_kurs`, `created_by`
        $uniks = CostType::all();
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

    public function store(StorePaymentRequest $request)
    {
        // Validatsiyadan muvaffaqiyatli o'tgan qiymatlarni olish
        $validatedData = $request->validated();
    
        $kurs = DB::table('valyuta_days')
            ->latest('id')
            ->value('kurs'); // eng oxirgi kurs qiymatini olish
    
        $naqd = $validatedData['naqd'] ?? 0;
        $plastik = $validatedData['plastik'] ?? 0;
        $tolov_summa = $naqd + $plastik;
    
        // Agar to'lov summasi kiritilmasa, xabar bilan qaytarish
        if ($tolov_summa <= 0) {
            return response()->json([
                'success' => true,
                'message' => 'Tulov summasi nolga teng bo`lishi mumkin emas!'
            ]);
        }
    
        // Oldingi to'lovlar ma'lumotlarini olish
        $result = DB::table('sales as s')
            ->select('s.id', 's.unik', 's.nasiya', 'p.naqd', 'p.plastik')
            ->leftJoin('payments as p', 's.unik', '=', 'p.unik_id')
            ->where('s.client_id', $validatedData['client'])
            ->where('s.valyuta_id', $validatedData['valyuta'])
            ->whereNotNull('s.paid_at')
            ->whereNull('s.deleted_at')
            ->whereNull('s.closed_at')
            ->groupBy('s.unik')
            ->orderBy('s.id', 'asc')
            ->get();
    
        // To'lovni yozish
        foreach ($result as $item) {
            $qoldiq = ($item->nasiya ?? 0) - ($item->naqd ?? 0) - ($item->plastik ?? 0);
    
            if ($tolov_summa < $qoldiq) {
                $this->insertPayment($item->unik, $validatedData['client'], $validatedData['valyuta'], $naqd, $plastik, $kurs, $validatedData['user_id']);
                break;
            }
            elseif($tolov_summa == $qoldiq) {
                $this->insertPayment($item->unik, $validatedData['client'], $validatedData['valyuta'], $naqd, $plastik, $kurs, $validatedData['user_id']);
                $this->updateSales($item->id);
                break;
            }
            else {
                if($naqd >= $qoldiq){
                    $naqdIn = $qoldiq;
                    $plastikIn = 0;
                    $naqd -= $qoldiq;
                }
                elseif($plastik >= $qoldiq){
                    $plastikIn = $qoldiq;
                    $naqdIn = 0;
                    $plastik -= $qoldiq;
                }
                else {
                    $naqdIn = $naqd;//60
                    $plastikIn = $qoldiq - $naqdIn;//100-60
                    $naqd = 0;
                    $plastik -= $plastikIn;//
                }
                $this->insertPayment($item->unik, $validatedData['client'], $validatedData['valyuta'], $naqdIn, $plastikIn, $kurs, $validatedData['user_id']);
                $this->updateSales($item->id);
                $tolov_summa -= $qoldiq;
                
            }
        }
    
        // Muvaffaqiyatli javob qaytarish
        session()->flash('success', 'To\'lov muvaffaqiyatli saqlandi!');
        return response()->json([
            'success' => true,
            'message' => 'To\'lov muvaffaqiyatli saqlandi!',
            'client' => $validatedData['client']
        ]);
    }

    private function insertPayment($unik, $client, $valyuta, $naqd, $plastik, $kurs, $userid)
    {
        DB::table('payments')->insert([
            'unik_id' => $unik,
            'client_id' => $client,
            'valyuta_id' => $valyuta,
            'naqd' => $naqd,
            'plastik' => $plastik,
            'valyuta_kurs' => $kurs,
            'created_by' => $userid,
            'created_at' => now(), // agar 'created_at' ustuni mavjud bo'lsa
            'updated_at' => now()  // agar 'updated_at' ustuni mavjud bo'lsa
        ]);
    }

    private function updateSales($id) {
        DB::table('sales')
            ->where('id', $id)
            ->update([
                'updated_at' => now(),
                'closed_at' => now(),
            ]);
    }
    
    public function show(string $client_id){
        return $this->getSales('Muvafaqqiyatli', $client_id);
    }
    
    public function edit(string $payment_id){
        $payment = DB::table('payments')
                        ->select(
                            'payments.id as payment_id',
                            'payments.naqd',
                            'payments.plastik',
                            DB::raw("DATE_FORMAT(payments.created_at, '%d.%m.%Y %H:%i') as created_at"), // Formatlangan vaqt
                            'clients.id',
                            'clients.client'
                        )
                        ->join('clients', 'payments.client_id', '=', 'clients.id')
                        ->where('payments.id', $payment_id)
                        ->first();

        $client_id = $payment->id;
        $totalNasiya = DB::table('sales')
                            ->where('client_id', $client_id)
                            ->whereNull('deleted_at')
                            ->sum('nasiya');

        $paymentSumma =  DB::table('payments')
                                ->selectRaw('SUM(naqd + plastik) AS summa')
                                ->where('client_id', $client_id)
                                ->whereNull('deleted_at')
                                ->value('summa');

        return response()->json([
                'payment' => $payment,
                'qarz' => $totalNasiya - $paymentSumma,
            ]);

    }


    public function getSales($message, $client_id)
    {
        $i = 1; // Ketma-ket raqam uchun boshlang'ich qiymat
    
        // 'sales' jadvalidan 'unik' ustunini olish
        $sales = Sale::select('id', 'valyuta_id', 'unik', 'naqd', 'plastik', 'nasiya', 'paid_at', 'closed_at')
                    ->where('client_id', $client_id) // client_id parametr bilan berildi
                    ->whereNull('deleted_at')
                    ->get()
                    ->map(function($item) use (&$i) {
                        $valyuta = Valyuta::find($item->valyuta_id)->valyuta;
    
                        if (empty($item->paid_at)) {
                            $status = '<span class="badge badge-rounded badge-success">Mavjud emas</span>';
                            $qarz = 0;
                        } elseif (!empty($item->closed_at)) {
                            $vaqt = date('d.m.Y', strtotime($item->closed_at));
                            $status = '<span class="badge badge-rounded badge-primary">'.$vaqt.' sanada yopilgan</span>';
                            $qarz = 0;
                        } else {
                            $tulovSumma = DB::table('payments')
                                            ->where('unik_id', $item->unik)
                                            ->whereNull('deleted_at')
                                            ->sum(DB::raw('naqd + plastik'));
                            if ($item->closed_at < now()) {
                                $status = '<span class="badge badge-rounded badge-danger">Muddati o`tgan</span>';
                                $qarz = $item->nasiya - $tulovSumma;
                            } else {
                                $status = '<span class="badge badge-rounded badge-warning">Qarzdor</span>';
                                $qarz = $item->nasiya - $tulovSumma;
                            }
                        }
    
                        $paid_at = $item->paid_at ? date("d.m.Y", strtotime($item->paid_at)) : null;
                        return [
                            'i' => $i++,
                            'unik' => $item->unik,
                            'naqd' => $item->naqd,
                            'plastik' => $item->plastik,
                            'nasiya' => $item->nasiya,
                            'holat' => $status,
                            'qarz_summasi' => $qarz,
                            'yopilish_vaqti' => $paid_at,
                        ];
                    })
                    ->filter() // null qiymatlarni olib tashlash
                    ->values(); // Qayta indekslash uchun
        
        //to'lovlarni olish
        $payments = DB::table('payments as p')
                ->join('valyutas as v', 'p.valyuta_id', '=', 'v.id')
                ->select('p.id','p.unik_id', 'p.naqd', 'p.plastik', 'p.created_at', 'v.valyuta')
                ->where('p.client_id', $client_id)
                ->whereNull('p.deleted_at')
                ->get();
        // JSON formatda natijani qaytarish
        return response()->json([
            'sales' => $sales,
            'payments' => $payments,
            'message' => $message,
        ]);
    }
    
    public function update(Request $request, string $id)
    {
        $paymentId = $request->id;
        $clientId = DB::table('payments')->where('id', $paymentId)->value('client_id');

        
        DB::table('payments')
            ->where('id', $paymentId)
            ->update([
                'deleted_by' => auth()->user()->id,
                'delete_reason' => 'xatolik',
                'deleted_at' => now(), // yoki Carbon::now() dan foydalanishingiz mumkin
            ]);
        return $this->getSales('Tovar o\'chirildi!', $clientId);
    }

}

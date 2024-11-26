<?php

namespace App\Services;
use App\Models\Sklad_in\FakturaTovar;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sklad\Madel;
use App\Models\Unik;
use App\Models\Sentsms;
use Illuminate\Support\Facades\DB;


class FunctionsServices
{
    public function getAstatkaProducts()
    {
        $items = FakturaTovar::whereNull('deleted_at')
            ->get()
            ->groupBy('madel_id')
            ->map(function ($group) {
                $item = $group->first(); // Guruhdagi birinchi element
                $madel_id = $item->madel_id;
    
                // Guruhdagi barcha faktura tovarlarining sonini hisoblash
                $totalSoni = $group->sum('soni');
    
                // SalesTovar jadvalidan ushbu madelga tegishli sotilgan tovarlar sonini hisoblash
                $tovarCnt = SalesTovar::whereNull('deleted_at')
                    ->where('madel_id', $madel_id)
                    ->sum('soni');
                
                //mavjud tovar soni
                $soni = $totalSoni - $tovarCnt;
                
                // Guruhdagi barcha `kirim_narx` qiymatlarining o'rtacha qiymatini olish
                $averageKirimNarx = $this->getAvarageKirimNarx($soni, $madel_id);
    
                return [
                    'id' => $item->id,
                    'tname' => 
                        ($item->Madel->id ?? '') . ". " .
                        ($item->Madel->Type->type ?? '') . " " .
                        ($item->Madel->Brand->brand ?? '') . " " .
                        ($item->Madel->madel ?? ''),
                    'soni' => $soni,
                    'valyuta' => $item->FakturaInput && $item->FakturaInput->Valyuta ? $item->FakturaInput->Valyuta->valyuta : null,
                    'kirim_narx' => $averageKirimNarx,
                    'sotuv_narx' => $item->Madel->sotuv_narx ?? 0,
                    'madel_id' => $madel_id,
                ];
            })
            ->filter(function ($item) {
                return $item['soni'] > 0; // Faqat `soni` 0 dan katta bo'lgan elementlarni saqlash
            });
    
        return $items;
    }
    
    public function getAvarageKirimNarx($soni, $model_id){
        $faktra_ids = DB::table('faktura_inputs')
                    ->whereNull('deleted_at')
                    ->orderBy('id', 'desc')
                    ->pluck('id');
        $exist_fakturas = array();
        foreach($faktra_ids as $id){
            $jamiSoni = DB::table('faktura_tovars')
                            ->where('faktura_id', $id)
                            ->where('madel_id', $model_id)
                            ->whereNull('deleted_at')
                            ->sum('soni');
            $soni -= $jamiSoni;
            array_push($exist_fakturas, $id);
            if($soni <= 0)
                break;
        }
        
        //o'rtacha kirim narxni hisoblash
        $queryAv = DB::table('faktura_tovars')
                        ->whereIn('faktura_id', $exist_fakturas) // implode o‘rniga whereIn ishlatiladi.
                        ->where('madel_id', $model_id) // 'madel_id' qiymatini belgilang.
                        ->whereNull('deleted_at')
                        ->avg('kirim_narx'); // Laravelda `AVG` uchun `avg()` metodi ishlatiladi.
        
        return $queryAv;
    }
    
    public function getSales($message, $format, $unik)
    {
        // Tashqi hisoblagich o'zgaruvchi
        $i = 0;
    
        // Fetch SalesTovar and group by madel_id
        $items = SalesTovar::whereNull('deleted_at')
            ->where('unik_id', $unik)
            ->get()
            ->groupBy('madel_id')
            ->map(function($group) use (&$i) {  // $i tashqi o'zgaruvchi bo'lib kiritilgan
                $totalSoni = $group->sum('soni');
                $totalSumma = $group->sum(function ($item) {
                    return $item->soni * $item->chiqim_narx;
                });
    
                $firstItem = $group->first(); // Guruhdagi birinchi element
    
                // Agar $firstItem mavjud bo'lmasa, o'tkazib yuboriladi
                if (!$firstItem) {
                    return null;
                }
    
                // Ketma-ket tartibni oshirish
                $i++;
    
                return [
                    'i' => $i, // Ketma-ket tartibda 'i' qiymati
                    'id' => $firstItem->id,
                    'tname' => ($firstItem->Madel && $firstItem->Madel->Type && $firstItem->Madel->Brand) ? 
           $firstItem->Madel->id . ". ". $firstItem->Madel->Type->type . " " . $firstItem->Madel->Brand->brand . " " . $firstItem->Madel->madel : 
           'Ma始lumot yetarli emas',
                    'madel' => $firstItem->Madel->madel ?? 'Ma始lumot yetarli emas',
                    'soni' => $totalSoni,
                    'chiqim_narx' => $firstItem->chiqim_narx,
                    'chegirma' => $firstItem->chegirma*$totalSoni,
                    'summa' => $totalSumma,
                ];
            })
            ->filter(); // null qiymatlarni olib tashlash
            
        $sales = DB::table('sales as s')
            ->join('clients as c', 's.client_id', '=', 'c.id')
            ->where('s.unik', $unik)
            ->whereNull('s.deleted_at')
            ->select('c.client', 's.unik', 's.naqd', 's.plastik', 's.nasiya', 's.chegirma')
            ->get();
    
        // Return a JSON response with sales data and the original message
        if(!empty($format) && $format=='json')
            return response()->json([
                'sale_tovar' => $items,
                'sales' => $sales,
                'message' => $message,
            ]);
        else
            return [
                'sale_tovar' => $items,
                'sales' => $sales,
                'message' => $message,
                'unik' => $unik
            ];
    }
    
    public function formatPhoneNumber($phoneNumber) {
        // Telefon raqamini faqat raqamlar bilan tozalash
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Raqamlarning kerakli qismlarini ajratib olish
        $countryCode = substr($cleaned, 0, 2); // AA
        $part1 = substr($cleaned, 2, 3);       // xxx
        $part2 = substr($cleaned, 5, 2);       // yy
        $part3 = substr($cleaned, 7, 2);       // zz

        // Formatlangan telefon raqamini qaytarish
        return "($countryCode) $part1-$part2-$part3";
    }
    
    public function insertLog($phone, $text, $log, $servise){
        $sentMessage = SentSms::create([
                            'phone' => $phone,
                            'text' => $text,
                            'result' => $log,
                            'servise' => $servise
                        ]);
        return empty($sentMessage) ? false : true;
    }
    
    public function sendSms($phone, $sms_text){
        $url = 'https://routee.sayqal.uz/sms/TransmitSMS';
        $curl = curl_init();
        $userName = "xasanovos";
    	$secretKey = "f76d0f62e9f576f8814d33ea0df91a0a";
    	$utime=time();
    	$servise = intval(2);
        $Access = "TransmitSMS {$userName} {$secretKey} {$utime}";
        $mytoken = md5($Access);
        
        curl_setopt_array($curl, [
    		CURLOPT_URL => $url,
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_ENCODING => "",
    		CURLOPT_MAXREDIRS => 10,
    		CURLOPT_TIMEOUT => 30,
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		CURLOPT_CUSTOMREQUEST => "POST",
    		CURLOPT_POSTFIELDS => json_encode([
                "utime" => $utime,
                "username" => $userName,
            	"service" => [
                    "service"=>$servise,
                    // "nickname"=>$username,
                    ],
                "message" => [
                    "smsid" => 101,
                    "phone" => $phone,
                    "text" => $sms_text
                ]
    			
    		], JSON_UNESCAPED_UNICODE),
    		CURLOPT_HTTPHEADER => [ 'Content-Type: application/json','X-Access-Token:'.$mytoken ]
    	]);
    
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    	
    	if ($err) 
    	{
        	$rez= "cURL Error #:" . $err;
        	$this->insertLog($phone, $sms_text, $rez, $servise);
        	curl_close($curl);
            return false;
        } 
        else 
        {
        	$a= json_decode($response);
        	if(!empty($a->errorCode))
        	{
        	    $rez= "Response Error #:" .$a->errorCode." ".$a->errMsg;
        	    $this->insertLog($phone, $sms_text, $rez, $servise);
            	curl_close($curl);
                return false;
        	}
        	  
        	elseif(!empty($a->transactionid))
        	{
        	    $rez= "Success #:".$a->transactionid." ".$a->smsid;
        	    $this->insertLog($phone, $sms_text, $rez, $servise);
            	curl_close($curl);
                return true;
        	}
    
        }
    }

}

<?php

namespace App\Http\Requests\Investor;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestorInputRequest extends FormRequest
{
    /**
     * So'rovni kim foydalanishini tekshirish (ixtiyoriy).
     */
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa, false qilib qo'yish mumkin
    }

    /**
     * Validatsiya oldidan ma'lumotlarni tayyorlash.
     */
    public function prepareForValidation()
    {
        $this->merge([
            'naqd' => $this->naqd ? floatval(preg_replace('/[^\d.]/', '', $this->naqd)) : 0,
            'plastik' => $this->plastik ? floatval(preg_replace('/[^\d.]/', '', $this->plastik)) : 0,
        ]);
         
    }
    
    /**
     * Validatsiya qoidalari.
     */
    public function rules()
    {
        return [
            'investor' => 'required|integer|exists:investors,id',
            'naqd' => 'nullable|numeric',
            'plastik' => 'nullable|numeric',
            'valyuta' => 'required|integer|exists:valyutas,id',
            'izoh' => 'required|string|min:5',
        ];
    }

    /**
     * Validatsiya xabarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'investor.required' => 'Investor maydoni to‘ldirilishi shart.',
            'investor.integer' => 'Investor ID raqam bo‘lishi kerak.',
            'investor.exists' => 'Kiritilgan investor ID mavjud emas.',
            'naqd.numeric' => 'Naqd summasi faqat raqamlardan iborat bo‘lishi kerak.',
            'plastik.numeric' => 'Plastik summasi faqat raqamlardan iborat bo‘lishi kerak.',
            'valyuta.required' => 'Valyuta maydoni to‘ldirilishi shart.',
            'valyuta.integer' => 'Valyuta ID raqam bo‘lishi kerak.',
            'valyuta.exists' => 'Kiritilgan valyuta ID mavjud emas.',
            'izoh.required' => 'Izoh maydoni to‘ldirilishi shart.',
            'izoh.string' => 'Izoh matn bo‘lishi kerak.',
            'izoh.min' => 'Izoh kamida 5 ta belgidan iborat bo‘lishi kerak.',
        ];
    }

    /**
     * Qo'shimcha validatsiya qoidalari.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $naqd = $this->input('naqd', 0);
            $plastik = $this->input('plastik', 0);
    
            $sum = $naqd + $plastik;
    
    
            if ($sum <= 1) {
                $validator->errors()->add('total', 'Naqd va plastik maydonlar yig‘indisi 1 dan katta bo‘lishi kerak.');
            }
            // dd($this->all()); // Shu yerdagi ma'lumotlarni ko'rib chiqing
        });
    }
}

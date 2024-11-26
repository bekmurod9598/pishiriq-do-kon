<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }
    
    public function prepareForValidation()
    {
        // 'narxi' qiymatini faqat sonli va nuqtadan iborat qilib tozalash
        $this->merge([
            'chegirma' => floatval(preg_replace('/[^\d.]/', '', $this->chegirma)),
        ]);
    }

    public function rules()
    {
        return [
            'tovar_id' => 'required|integer|exists:madels,id',  // 'madels' jadvalidagi 'id' ustunini tekshirish
            'chegirma' => 'required|numeric',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'soni' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'tovar' => 'string',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'tovar_soni' => 'integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    public function messages()
    {
        return [
            'tovar_id.required' => 'Joriy tovar model idsi majburiy.',
            'tovar_id.integer' => 'Joriy tovar model idsi integer turida bo`lishi shart.',
            'tovar_id.exists' => 'Joriy tovar model idsi users jadvalida mavjud emas.',
            'chegirma.required' => 'chegirma maydoni to‘ldirilishi shart.',
            'chegirma.numeric' => 'chegirma raqamlardan iborat bo‘lishi kerak.',
            'soni.required' => 'Tovar soni bo\'sh bo‘lishi mumkin emas.',
            'soni.integer' => 'Tovar soni butun son bo`lishi shart.',
            'tovar.integer' => 'Tovar nomi matn turida bo`lishi shart.',
            'tovar_soni.integer' => 'Joriy tovar soni integer turida bo`lishi shart.',
        ];
    }
}

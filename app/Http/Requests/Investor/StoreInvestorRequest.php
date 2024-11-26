<?php

namespace App\Http\Requests\Investor;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvestorRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

     public function rules()
    {
        return [
            'investor' => [
                                'required',
                                'max:100',
                                Rule::unique('investors')->whereNull('deleted_at'),
                            ],
            'adress' => 'required|max:255',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'phone' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    
    public function messages()
    {
        return [
            'investor.required' => 'Yangi investor maydoni to‘ldirilishi shart.',
            'investor.unique' => 'Bu investor allaqachon mavjud.',
            'investor.max' => 'Yangi investor 100 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'adress.required' => 'Investor manzili bo\'sh bo‘lishi mumkin emas.',
            'adress.max' => 'Investor manzili 255 tadan ortiq belgi bo‘lishi mumkin emas.',
            'phone.required' => 'Investor telefon raqami bo\'sh bo‘lishi mumkin emas.',
            'phone.integer' => 'Investor telefon raqami integer turida bo`lishi shart.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer brandida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

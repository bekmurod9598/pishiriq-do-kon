<?php

namespace App\Http\Requests\Kassa;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCostTypeRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

     public function rules()
    {
        return [
            'type' => [
                                'required',
                                'max:50',
                                Rule::unique('cost_types')->whereNull('deleted_at'),
                            ],
            // 'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'user_id' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    
    public function messages()
    {
        return [
            'type.required' => 'Yangi xarajat turi maydoni to‘ldirilishi shart.',
            'type.unique' => 'Bu xarajat turi allaqachon mavjud.',
            'type.max' => 'Yangi xarajat turi 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer brandida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

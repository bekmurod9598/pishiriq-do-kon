<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }

    public function rules()
    {
        return [
            'client' => [
                                'required',
                                'max:50',
                                Rule::unique('clients')->whereNull('deleted_at'),
                            ],

            'adress' => 'required|max:255',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'phone' => 'required|integer:unique',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'phone_extra' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
            // 'user_id' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    public function messages()
    {
        return [
            'client.required' => 'Mijoz ism-familasi maydoni to‘ldirilishi shart.',
            'client.unique' => 'Bu mijoz allaqachon mavjud.',
            'client.max' => 'Yangi mijoz nomi 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'adress.required' => 'Mijoz manzili bo\'sh bo‘lishi mumkin emas.',
            'adress.max' => 'Mijoz manzili 255 tadan ortiq belgi bo‘lishi mumkin emas.',
            'phone.required' => 'Mijoz telefon raqami bo\'sh bo‘lishi mumkin emas.',
            'phone.integer' => 'Mijoz telefon raqami integer turida bo`lishi shart.',
            'phone.unique' => 'Bu telefon raqam bilan mijoz allaqachon mavjud.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

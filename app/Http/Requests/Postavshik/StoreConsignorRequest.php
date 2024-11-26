<?php

namespace App\Http\Requests\Postavshik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsignorRequest extends FormRequest
{
    /**
     * So'rovni kim foydalanishini tekshirish (ixtiyoriy).
     */
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }

    /**
     * Validatsiya qoidalari.
     */
    public function rules()
    {
        return [
            'consignor' => [
                                'required',
                                'max:50',
                                Rule::unique('consignors')->whereNull('deleted_at'),
                            ],

            'adress' => 'required|max:255',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'phone' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
            // 'user_id' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'consignor.required' => 'Yangi postavshik maydoni to‘ldirilishi shart.',
            'consignor.unique' => 'Bu postavshik allaqachon mavjud.',
            'consignor.max' => 'Yangi postavshik nomi 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'adress.required' => 'Postavshik manzili bo\'sh bo‘lishi mumkin emas.',
            'adress.max' => 'Postavshik manzili 255 tadan ortiq belgi bo‘lishi mumkin emas.',
            'phone.required' => 'Postavshik telefon raqami bo\'sh bo‘lishi mumkin emas.',
            // 'phone.max' => 'Postavshik telefon raqami 9 tadan ortiq raqam bo‘lishi mumkin emas.',
            'phone.integer' => 'Postavshik telefon raqami integer turida bo`lishi shart.',
            // 'phone.min' => 'Postavshik telefon raqami 9 tadan kam belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

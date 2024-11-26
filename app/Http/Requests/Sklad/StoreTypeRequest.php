<?php

namespace App\Http\Requests\Sklad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTypeRequest extends FormRequest
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
            'type' => [
                                'required',
                                'max:50',
                                Rule::unique('types')->whereNull('deleted_at'),
                            ],

            // 'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
            'user_id' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'type.required' => 'Yangi tur maydoni to‘ldirilishi shart.',
            'type.unique' => 'Bu tur allaqachon mavjud.',
            'type.max' => 'Yangi tur 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

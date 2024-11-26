<?php

namespace App\Http\Requests\Sklad;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'brand' => [
                                'required',
                                'max:50',
                                Rule::unique('brands')->whereNull('deleted_at'),
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
            'brand.required' => 'Yangi brand maydoni to‘ldirilishi shart.',
            'brand.unique' => 'Bu brand allaqachon mavjud.',
            'brand.max' => 'Yangi brand 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer brandida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

<?php

namespace App\Http\Requests\Sklad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMadelRequest extends FormRequest
{
    /**
     * So'rovni kim foydalanishini tekshirish (ixtiyoriy).
     */
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }
    
    public function prepareForValidation()
    {
        // 'narxi' qiymatini faqat sonli va nuqtadan iborat qilib tozalash
        $this->merge([
            'sotuv_narx' => floatval(preg_replace('/[^\d.]/', '', $this->sotuv_narx)),
        ]);
    }

    /**
     * Validatsiya qoidalari.
     */
    public function rules()
    {
        return [
            'type' => 'required|integer',
            'brand' => 'required|integer',
            'madel' => [
                            'required',
                            'max:50',
                            Rule::unique('madels')->whereNull('deleted_at'),
                        ],
            'sotuv_narx' => 'required|numeric',
            'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'madel.required' => 'Yangi madel maydoni to‘ldirilishi shart.',
            'madel.unique' => 'Bu madel allaqachon mavjud.',
            'madel.max' => 'Yangi madel 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
            'type.required' => 'Tur tanlanishi idsi majburiy.',
            'type.integer' => 'Tur idsi integer turida bo`lishi shart.',
            'brand.required' => 'Brend tanlanishi majburiy.',
            'brand.integer' => 'Brend idsi integer turida bo`lishi shart.',
            'sotuv_narx.required' => 'Sotuv narx kiritilishi majburiy.',
            'sotuv_narx.numeric' => 'Sotuv narx raqamlardan iborat bo`lishi majburiy.',
        ];
    }
}

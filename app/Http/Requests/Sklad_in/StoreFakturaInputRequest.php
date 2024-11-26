<?php

namespace App\Http\Requests\Sklad_in;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFakturaInputRequest extends FormRequest
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
        // 'kurs' qiymatini faqat sonli va nuqtadan iborat qilib tozalash
        $this->merge([
            'kurs' => floatval(preg_replace('/[^\d.]/', '', $this->kurs)),
        ]);
    }
    /**
     * Validatsiya qoidalari.
     */
    public function rules()
    {
        return [
            'consignor' => 'required|integer|exists:consignors,id',
            'valyuta' => 'required|integer|exists:valyutas,id',
            'kurs' => ['required', 'numeric', 'between:100,999999.99'],
            'faktura' => [
                            'required',
                            'max:50',
                            Rule::unique('faktura_inputs')->whereNull('deleted_at'),
                        ],
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
            'faktura.required' => 'Yangi faktura maydoni to‘ldirilishi shart.',
            'faktura.unique' => 'Bu faktura allaqachon mavjud.',
            'faktura.max' => 'Yangi fakturada 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi tizimda mavjud emas.',
            'consignor.required' => 'Postavshik tanlash majburiy.',
            'consignor.integer' => 'Postavshik idsi integer turida bo`lishi shart.',
            'consignor.exists' => 'Joriy postavshik tizimda mavjud emas.',
            'valyuta.required' => 'Valyuta tanlanishi majburiy.',
            'valyuta.integer' => 'Valyuta idsi integer turida bo`lishi shart.',
            'valyuta.exists' => 'Joriy valyuta tizimda mavjud emas.',
            'kurs.required' => 'Kurs kiritilishi majburiy.',
            'kurs.numeric' => 'Kurs raqamlardan iborat bo\'lishi kerak.',
            'kurs.between' => 'Milliy valyutada 100 so\'mdan kamga to\'g\'ri keladigan valyuta qolmadi.',
        ];
    }
}

<?php

namespace App\Http\Requests\Sklad_in;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFakturaTovarRequest extends FormRequest
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
            'kirim_narx' => floatval(preg_replace('/[^\d.]/', '', $this->kirim_narx)),
            'sotuv_narx' => floatval(preg_replace('/[^\d.]/', '', $this->sotuv_narx)),
        ]);
    }
    /**
     * Validatsiya qoidalari.
     */
    public function rules()
    {
        return [
            'faktura_id' => 'required|integer|exists:faktura_inputs,id',
            'madel' => 'required|integer|exists:madels,id',
            'soni' => 'required|integer|min:1',
            'kirim_narx' => 'required|numeric|min:1',
            'sotuv_narx' => 'required|numeric|min:1',
            'user_id' => 'required|integer|exists:users,id',

            // 'user_id' => 'required|integer',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'faktura_id.required' => 'Yangi faktura maydoni to‘ldirilishi shart.',
            'faktura_id.integer' => 'Faktura idsi integer tipida emas.',
            'faktura_id.exists' => 'Yangi faktura tizimda mavjud emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi tizimda mavjud emas.',
            'madel.required' => 'Model tanlash majburiy.',
            'madel.integer' => 'Model idsi integer turida bo`lishi shart.',
            'madel.exists' => 'Joriy model tizimda mavjud emas.',
            'soni.required' => 'Tovar soni tanlanishi majburiy.',
            'soni.min' => 'Tovar soni 0 dan katta bo`lishi shart.',
            'kirim_narx.required' => 'Tovar kirim narxi kiritilishi majburiy.',
            'kirim_narx.numeric' => 'Tovar kirim narxi raqamlardan iborat bo\'lishi kerak.',
            'kirim_narx.min' => 'Tovar kirim narxi 0 dan katta bo`lishi shart.',
            'sotuv_narx.required' => 'Tovar kirim narxi kiritilishi majburiy.',
            'sotuv_narx.numeric' => 'Tovar kirim narxi raqamlardan iborat bo\'lishi kerak.',
            'sotuv_narx.min' => 'Tovar kirim narxi 0 dan katta bo`lishi shart.',
        ];
    }
}

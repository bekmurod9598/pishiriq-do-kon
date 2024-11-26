<?php

namespace App\Http\Requests\Sklad_out;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiseRequest extends FormRequest
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
            'narxi' => floatval(preg_replace('/[^\d.]/', '', $this->narxi)),
        ]);
    }
    
    public function rules()
    {
        return [
            'nomi' => [
                                'required',
                                'max:50',
                                Rule::unique('servises')->whereNull('deleted_at'),
                            ],
            'narxi' => 'required|numeric|min:1',
            'valyuta' => 'required|integer|exists:valyutas,id',
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
            'nomi.required' => 'Yangi servis maydoni to‘ldirilishi shart.',
            'nomi.unique' => 'Bu servis allaqachon mavjud.',
            'nomi.max' => 'Yangi servis nomi 50 tadan ortiq belgi bo‘lishi mumkin emas.',
            'narxi.required' => 'Servis narxi majburiy.',
            'narxi.numeric' => 'Servis narxi raqam turida bo`lishi shart.',
            'narxi.min' => 'Servis narxi 1 dan kam bo\'lishi mumkin emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
            'valyuta.required' => 'Joriy valyuta idsi majburiy.',
            'valyuta.integer' => 'Joriy valyuta idsi integer turida bo`lishi shart.',
            'valyuta.exists' => 'Joriy valyuta idsi valyuta jadvalida mavjud emas.',
        ];
    }
}

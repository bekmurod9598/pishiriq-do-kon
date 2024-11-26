<?php

namespace App\Http\Requests\Sklad_out;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
        $this->merge([
            'naqd' => $this->naqd ? floatval(preg_replace('/[^\d.]/', '', $this->naqd)) : 0,
            'plastik' => $this->plastik ? floatval(preg_replace('/[^\d.]/', '', $this->plastik)) : 0,
        ]);
    }
    
    public function rules()
    {
        return [
            'client' => 'required|integer',
            'naqd' => 'nullable|numeric',
            'plastik' => 'nullable|numeric',
            'valyuta' => 'required|integer|exists:valyutas,id',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'client.required' => 'Mijoz tanlanishi shart.',
            'client.integer' => 'Mijoz idsi raqamlardan iborat bo`lishi kerak.',
            'naqd.numeric' => 'Naqd to`lov summasi raqamlardan iborat bo`lishi kerak.',
            'plastik.numeric' => 'Plastik to`lov summasi raqamlardan iborat bo`lishi kerak.',
            'valyuta.required' => 'Valyuta turi tanlanishi shart.',
            'valyuta.integer' => 'Valyuta turi idsi raqamlardan iborat bo`lishi kerak.',
            'valyuta.exists' => 'Valyuta turi valyutalar jadvalida mavjud emas.',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

<?php

namespace App\Http\Requests\Kassa;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostRequest extends FormRequest
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
            'summa' => $this->summa ? floatval(preg_replace('/[^\d.]/', '', $this->summa)) : 0,
        ]);
    }
    
    public function rules()
    {
        return [
            'type' => 'required|integer',
            'postavshik' => 'nullable|integer|exists:consignors,id',
            'summa' => 'required|numeric|min:1',
            'pay_type' => 'required|string',
            'valyuta' => 'required|integer|exists:valyutas,id',
            'izoh' => 'required|string|min:5',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Xatolik habarlarini o'zbek tilida qaytarish.
     */
    public function messages()
    {
        return [
            'type.required' => 'Yangi xarajat turi maydoni to‘ldirilishi shart.',
            'type.integer' => 'xarajat turi idsi raqamlardan iborat bo`lishi kerak.',
            'type.exists' => 'Joriy xarajat turi turlar jadvalida mavjud emas.',
            'postavshik.integer' => 'postavshik idsi raqamlardan iborat bo`lishi kerak.',
            'postavshik.exists' => 'Joriy postavshik postavshiklar jadvalida mavjud emas.',
            'summa.required' => 'Yangi xizmat turi maydoni to‘ldirilishi shart.',
            'summa.integer' => 'Xizmat turi idsi raqamlardan iborat bo`lishi kerak.',
            'summa.min' => 'Xarajat 1 dan kam bo`lmaydi',
            'pay_type.required' => 'To`lov usuli maydoni to‘ldirilishi shart.',
            'pay_type.string' => 'To`lov usuli harflardan iborat bo`lishi kerak.',
            'valyuta.required' => 'Yangi valyuta turi maydoni to‘ldirilishi shart.',
            'valyuta.integer' => 'valyuta turi idsi raqamlardan iborat bo`lishi kerak.',
            'valyuta.exists' => 'Joriy valyuta turi valyutalar jadvalida mavjud emas.',
            'izoh.required' => 'Izoh maydoni to‘ldirilishi shart.',
            'izoh.string' => 'Izoh harflardan iborat bo`lishi kerak.',
            'izoh.min' => 'Izoh kamida 5 ta harfdan iborat bo`lishi kerak',
            'user_id.required' => 'Joriy foydalanuvchi idsi majburiy.',
            'user_id.integer' => 'Joriy foydalanuvchi idsi integer brandida bo`lishi shart.',
            'user_id.exists' => 'Joriy foydalanuvchi idsi users jadvalida mavjud emas.',
        ];
    }
}

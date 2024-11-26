<?php

namespace App\Http\Requests\Sklad_out;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalesTovarRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }
    
    public function prepareForValidation()
    {
        $this->merge([
            'naqd' => $this->naqd ? floatval(preg_replace('/[^\d.]/', '', $this->naqd)) : 0,
            'plastik' => $this->plastik ? floatval(preg_replace('/[^\d.]/', '', $this->plastik)) : 0,
            'nasiya' => $this->nasiya ? floatval(preg_replace('/[^\d.]/', '', $this->nasiya)) : 0,
            'chegirma' => floatval(preg_replace('/[^\d.]/', '', $this->chegirma)),
        ]);
    }

    public function rules()
    {
        return [
            'unik' => 'required|integer|exists:uniks,id',  // 'uniks' jadvalidagi 'id' ustunini tekshirish
            'client' => 'required|integer|exists:clients,id',  // 'clients' jadvalidagi 'id' ustunini tekshirish
            'naqd' => 'nullable|numeric',  // Bo'sh kiritilsa 0 bo'ladi
            'plastik' => 'nullable|numeric',  // Bo'sh kiritilsa 0 bo'ladi
            'nasiya' => 'nullable|numeric',  // Bo'sh kiritilsa 0 bo'ladi
            'nasiya_sanasi' => 'nullable|date',  
            'chegirma' => 'required|numeric',  
            'valyuta' => 'required|integer|exists:valyutas,id',  // 'valyuta' jadvalidagi 'id' ustunini tekshirish
            'user_id' => 'required|integer|exists:users,id',  // 'users' jadvalidagi 'id' ustunini tekshirish
        ];
    }


    public function messages()
    {
        return [
            'unik.required' => 'Joriy unik idsi majburiy.',
            'unik.integer' => 'Joriy unik idsi integer turida bo`lishi shart.',
            'unik.exists' => 'Joriy unik idsi uniks jadvalida mavjud emas.',
            'naqd.numeric' => 'Naqd summasi faqat raqamlardan iborat bo`lishi shart.',
            'plastik.numeric' => 'plastik summasi faqat raqamlardan iborat bo`lishi shart.',
            'nasiya.numeric' => 'nasiya summasi faqat raqamlardan iborat bo`lishi shart.',
            'nasiya_sanasi.date' => 'nasiyani to`lash vaqti noto`g`ri formatda kiritildi .',
            'chegirma.required' => 'chegirma maydoni to‘ldirilishi shart.',
            'chegirma.numeric' => 'chegirma raqamlardan iborat bo‘lishi kerak.',
            'valyuta.required' => 'Joriy valyuta idsi majburiy.',
            'valyuta.integer' => 'Joriy valyuta idsi integer turida bo`lishi shart.',
            'valyuta.exists' => 'Joriy valyuta idsi valyutas jadvalida mavjud emas.',
            'user_id.required' => 'Joriy user idsi majburiy.',
            'user_id.integer' => 'Joriy user idsi integer turida bo`lishi shart.',
            'user_id.exists' => 'Joriy user idsi users jadvalida mavjud emas.',
        ];
    }
}

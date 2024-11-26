<?php

namespace App\Http\Requests\Kassa;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreKunlikHisobotRequest extends FormRequest
{
    /**
     * So'rovni kim foydalanishini tekshirish (ixtiyoriy).
     */
    public function authorize()
    {
        return true; // Agar autentifikatsiya kerak bo'lsa false qilib qo'yish mumkin
    }

    public function rules()
    {
        return [
            'date1' => 'required|date|before_or_equal:date2',
            'date2' => 'required|date|after_or_equal:date1',
        ];
    }

    public function messages()
    {
        return [
            'date1.required' => 'Boshlanish sanasini kiriting.',
            'date1.date' => 'Boshlanish sanasi to‘g‘ri formatda emas.',
            'date1.before_or_equal' => 'Boshlanish sanasi tugash sanasi bilan bir xil yoki undan oldin bo‘lishi kerak.',
            'date2.required' => 'Tugash sanasini kiriting.',
            'date2.date' => 'Tugash sanasi to‘g‘ri formatda emas.',
            'date2.after_or_equal' => 'Tugash sanasi boshlanish sanasi bilan bir xil yoki undan keyin bo‘lishi kerak.',
        ];
    }
}

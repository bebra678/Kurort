<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id' => ['required', 'integer', 'max:11'],
            'category_id' => ['required', 'integer', 'max:11'],
        ];
    }

    public function messages()
    {
        return [
            'card_id' => [
                'required' => 'Ошибка! Нет id карточки',
                'max' => 'Максимально допустимое значение: 11',
                'integer' => 'Ошибка! Передаваемое значение id_card должно быть integer'
            ],
            'category_id' => [
                'required' => 'Ошибка! Нет id категирии',
                'max' => 'Максимально допустимое значение: 11',
                'integer' => 'Ошибка! Передаваемое значение category_id должно быть integer'
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Ошибка валидации',
            'error' => $validator->errors()
        ]));
    }
}

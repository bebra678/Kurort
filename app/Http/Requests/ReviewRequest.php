<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id' => ['required', 'integer', 'max:11'],
            'text' => ['required', 'string', 'max:240', 'min:3'],
            'rating' => ['required', 'integer', 'between:1,5'],
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
            'text' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 240',
                'min' => 'Минимальное допустимое значение: 3',
            ],
            'rating' => [
                'required' => 'Ошибка! Нет оценки карточки',
                'between' => 'Ошибка! Передаваемое значение должно быть от 1 до 5',
                'integer' => 'Ошибка! Передаваемое значение rating должно быть integer'
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Ошибка валидации',
            'errors' => $validator->errors()
        ]));
    }
}

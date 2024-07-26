<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReactionImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'img_id' => ['required', 'integer', 'max:11'],
            'type' => ['required', 'integer', 'max:2', 'between:1,2'],
        ];
    }

    public function messages()
    {
        return [
            'img_id' => [
                'required' => 'Ошибка! Нет id изображения',
                'max' => 'Максимально допустимое значение: 1',
                'integer' => 'Ошибка! Передаваемое значение img_id должно быть integer',
            ],
            'type' => [
                'required' => 'Ошибка! Нет type',
                'max' => 'Максимально допустимое значение: 2',
                'integer' => 'Ошибка! Передаваемое значение type должно быть integer',
                'between' => 'Значение должно быть 1 или 2. Где 1 - это лайк, а 2 - это дизлайк',
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

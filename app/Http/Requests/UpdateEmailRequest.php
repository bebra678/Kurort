<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string', 'email', 'min:10','max:100', Rule::unique('users')],
        ];
    }

    public function messages()
    {
        return [
            'email' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 100',
                'min' => 'Минимальное допустимое значение: 10',
                'email' => 'Неправильный формат почты',
                'unique' => 'Данная почта занята',
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

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30', 'min:2', 'regex:/^[А-Я][\p{Cyrillic}-]+$/u'],
            'email' => ['required', 'string', 'email', 'min:10','max:100', Rule::unique('users')],
            'password' => ['required', 'string', 'max:100', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'name' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 30',
                'min' => 'Минимальное допустимое значение: 2',
                'regex' => 'Имя может содержать только: кириллицу и дефис'
            ],
            'email' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 100',
                'min' => 'Минимальное допустимое значение: 10',
                'email' => 'Неправильный формат почты',
                'unique' => 'Данная почта занята',
            ],
            'password' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 100',
                'min' => 'Минимальное допустимое значение: 6',
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

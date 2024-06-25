<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30', 'min:2', 'regex:/^[А-Я][\p{Cyrillic}-]+$/u'],
            'number' => ['nullable', 'regex:/^[\+7] \(\d{3}\) \d{3}-\d{2}-\d{2}$/', Rule::unique('users')],
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
            'password' => [
                'required' => 'Поле не должно быть пустым',
                'max' => 'Максимально допустимое значение: 100',
                'min' => 'Минимальное допустимое значение: 6',
            ],
            'number' => [
                'regex' => 'Номер телефона должен быть формата: +7 (999) 999-99-99',
                'unique' => 'Данный номер телефона занят',
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:30', 'min:2', 'regex:/^[А-Я][\p{Cyrillic}-]+$/u'],
            'email' => ['nullable', 'string', 'email', 'min:10','max:100', Rule::unique('users')],
            'password' => ['required', 'string', 'max:100', 'min:6'],
            'number' => ['nullable', 'regex:/^[\+7] \(\d{3}\) \d{3}-\d{2}-\d{2}$/', Rule::unique('users')],
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT':
                return [
                    'name' => ['nullable', 'string', 'max:30', 'min:2', 'regex:/^[А-Я][\p{Cyrillic}-]+$/u'],
                    'email' => ['nullable', 'string', 'email', 'min:10','max:100', Rule::unique('users')->ignore($this->email, 'email')],
                    'number' => ['nullable', 'regex:/^[\+7] \(\d{3}\) \d{3}-\d{2}-\d{2}$/', Rule::unique('users')->ignore($this->number, 'number')],
                ];
        }
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
//            '*.tags' => 'array',
//            'tags.addr:city' => 'nullable|string',
//            'tags.addr:housenumber' => 'nullable|string',
//            'tags.addr:street' => 'nullable|string',
//            'tags.name' => 'nullable|string',
//            'tags.coordinates' => 'nullable|array',
//            'tags.coordinates.lat' => 'nullable|numeric',
//            'tags.coordinates.lon' => 'nullable|numeric',


//
//            '*.addr.city' => 'nullable|string',
//            '*.addr.housenumber' => 'nullable|string',
//            '*.addr.street' => 'nullable|string',
//            '*.name' => 'nullable|string',
//            '*.coordinates' => 'nullable|array',
//            '*.coordinates.lat' => 'nullable|numeric',
//            '*.coordinates.lon' => 'nullable|numeric',

            '*.tags' => 'array',
            '*.lat' => 'sometimes|numeric',
            '*.lon' => 'sometimes|numeric',
            '*.tags.name' => 'sometimes|string|max:255',
            '*.tags.addr:housenumber' => 'nullable|string|max:255',
            '*.tags.addr:street' => 'nullable|string|max:255',
            '*.tags.inscription' => 'nullable|string|max:255',
            '*.tags.description' => 'nullable|string|max:255',
            '*.tags.website' => 'nullable|string|max:255',
            '*.tags.phone' => 'nullable|string|max:255',
        ];
    }
}

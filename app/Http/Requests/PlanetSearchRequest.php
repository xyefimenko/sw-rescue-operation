<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanetSearchRequest extends FormRequest
{
    public function rules()
    {
        return [
            'diameter' => 'nullable|numeric|min:0',
            'rotation_period' => 'nullable|numeric|min:0',
            'gravity' => 'nullable|string|max:255',
            'planet_search' => 'nullable|string|max:255',
        ];
    }
}

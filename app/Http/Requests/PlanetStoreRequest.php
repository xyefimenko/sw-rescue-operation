<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanetStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'rotation_period' => 'required|string|max:255',
            'orbital_period' => 'required|string|max:255',
            'diameter' => 'required|string|max:255',
            'climate' => 'required|string|max:255',
            'gravity' => 'required|string|max:255',
            'terrain' => 'required|string|max:255',
            'surface_water' => 'required|string|max:255',
            'population' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ];
    }
}

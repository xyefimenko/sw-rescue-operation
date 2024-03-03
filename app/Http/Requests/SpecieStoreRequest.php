<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecieStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'classification' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'average_height' => 'nullable|string|max:255',
            'skin_colors' => 'nullable|string|max:255',
            'hair_colors' => 'nullable|string|max:255',
            'eye_colors' => 'nullable|string|max:255',
            'average_lifespan' => 'nullable|string|max:255',
            'homeworld' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
        ];
    }
}

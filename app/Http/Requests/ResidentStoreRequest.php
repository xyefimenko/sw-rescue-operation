<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResidentStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'height' => 'required|string|max:255',
            'mass' => 'required|string|max:255',
            'hair_color' => 'required|string|max:255',
            'skin_color' => 'required|string|max:255',
            'eye_color' => 'required|string|max:255',
            'birth_year' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ];
    }
}

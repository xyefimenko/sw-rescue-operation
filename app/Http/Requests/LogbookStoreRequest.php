<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            '*.planet_id' => 'required|exists:planets,id',
            '*.mood' => 'required|string|max:255',
            '*.weather' => 'required|string|max:255',
            '*.gps_location' => 'required|string|max:255',
            '*.note' => 'required|string',
        ];
    }
}

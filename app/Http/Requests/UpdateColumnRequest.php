<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColumnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Protected by route middleware
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|in:gray,blue,green,yellow,red,orange,purple,pink,teal,indigo',
        ];
    }
}
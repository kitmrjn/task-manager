<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColumnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Protected by route middleware
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'color'       => 'required|string|in:gray,blue,green,yellow,red,orange,purple,pink,teal,indigo',
            'description' => 'nullable|string',
            'board_id'    => 'required|exists:boards,id',
        ];
    }
}
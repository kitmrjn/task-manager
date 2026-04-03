<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is already handled by the route middleware:
        // middleware('permission:can_create_tasks')
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'board_column_id' => 'required|exists:board_columns,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'assigned_to'     => 'nullable|exists:users,id',
            'priority'        => 'required|in:low,medium,high',
            'due_date'        => 'nullable|date',
            'start_date'      => 'nullable|date',
        ];
    }
}
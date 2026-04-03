<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can_access('can_edit_tasks');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'assigned_to'     => 'nullable',
            'priority'        => 'required|in:low,medium,high',
            'due_date'        => 'nullable|date',
            'start_date'      => 'nullable|date',
            'is_completed'    => 'nullable|boolean',
            'collaborators'   => 'nullable|string',
            'board_column_id' => 'nullable|exists:board_columns,id',
        ];
    }
}
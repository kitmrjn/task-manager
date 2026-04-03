<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'super_admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users',
            'role'           => 'required|string|exists:roles,slug', // <-- UPDATED
            'campaign_id'    => 'nullable|exists:campaigns,id',
            'team_leader_id' => 'nullable|exists:users,id',
        ];
    }
}
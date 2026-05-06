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
            'name'              => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users',
            'role'           => 'required|string|exists:roles,slug', // <-- UPDATED
            'campaign_id'    => 'nullable|exists:campaigns,id',
            'team_leader_id' => 'nullable|exists:users,id',
            'phone'             => 'nullable|string|max:20',
            'city'              => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:255',
            'country'           => 'nullable|string|max:100',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sss_number'        => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'tin_number'        => 'nullable|string|max:50',
            'pag_ibig_number'   => 'nullable|string|max:50',
            // Valid ID files
            'valid_id_sss_card'        => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'valid_id_philhealth_card' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'valid_id_tin_card'        => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'valid_id_pagibig_card'    => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'valid_id_passport'        => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'valid_id_drivers_license' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ];
    }
}
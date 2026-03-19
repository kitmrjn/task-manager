<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    /**
     * Update profile (name + email)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
        ]);

        $name = trim($request->first_name . ' ' . $request->last_name);

        $user->update([
            'name'  => $name,
            'email' => $request->email,
        ]);

        return redirect()->route('settings.index')
            ->with('success_profile', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->with('active_tab', 'account');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('settings.index')
            ->with('success_password', 'Password updated successfully!')
            ->with('active_tab', 'account');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => 'Incorrect password.'])
                ->with('active_tab', 'account');
        }

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('message', 'Your account has been deleted.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        // FIXED: Removed the role restriction so all users can access their own account settings.
        return view('settings');
    }

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

    public function updateBranding(Request $request)
    {
        // FIXED: Standardized to hierarchy logic
        if (!auth()->user()->isAtLeastAdmin()) abort(403, 'Unauthorized');

        $data = $request->validate([
            'app_name'        => 'nullable|string|max:30',
            'app_eyebrow'     => 'nullable|string|max:60',
            'app_headline'    => 'nullable|string|max:80',
            'app_description' => 'nullable|string|max:200',
            'app_logo'        => 'nullable|image|max:2048',
            'app_favicon'     => 'nullable|image|max:512',
        ]);

        if ($request->hasFile('app_logo')) {
            $data['app_logo'] = $request->file('app_logo')->store('branding', 'public');
        } else {
            unset($data['app_logo']);
        }

        if ($request->hasFile('app_favicon')) {
            $data['app_favicon'] = $request->file('app_favicon')->store('branding', 'public');
        } else {
            unset($data['app_favicon']);
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return back()->with([
            'success_branding' => 'Branding updated successfully!',
            'active_tab'       => 'branding',
        ]);
    }

    public function clearBranding($key)
    {
        // FIXED: Standardized to hierarchy logic
        if (!auth()->user()->isAtLeastAdmin()) abort(403, 'Unauthorized');

        if (!in_array($key, ['app_logo', 'app_favicon'])) abort(400);

        $setting = \App\Models\Setting::where('key', $key)->first();
        if ($setting && $setting->value) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($setting->value);
        }

        \App\Models\Setting::where('key', $key)->delete();
        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return back()->with([
            'success_branding' => 'Removed successfully.',
            'active_tab'       => 'branding',
        ]);
    }
}
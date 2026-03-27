<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandingController extends Controller
{
    // app/Http/Controllers/Admin/BrandingController.php
public function update(Request $request) {
    $data = $request->validate([
        'app_name' => 'string|max:50',
        'brand_color' => 'string',
        'app_logo' => 'image|nullable'
    ]);

    foreach($data as $key => $value) {
        if ($request->hasFile($key)) {
            $value = $request->file($key)->store('branding', 'public');
        }
        \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
    
    // Clear cache if you use it
    \Illuminate\Support\Facades\Cache::forget('site_settings');

    return back()->with('success', 'Branding updated successfully!');
}
}

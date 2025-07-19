<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::getSettings();
        
        return Inertia::render('settings/General', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'operation_hours' => 'required|string|max:1000',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $settings = GeneralSetting::getSettings();
        $settings->update($request->all());

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function apiIndex()
    {
        $settings = GeneralSetting::getSettings();
        return response()->json($settings);
    }
}

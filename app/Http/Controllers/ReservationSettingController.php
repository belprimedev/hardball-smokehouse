<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ReservationSetting;
use Illuminate\Http\Request;

class ReservationSettingController extends Controller
{
    public function index()
    {
        $settings = ReservationSetting::orderBy('id')->get();
        
        return Inertia::render('ReservationSettings/Index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:reservation_settings,id',
            'settings.*.opening_time' => 'required|date_format:H:i:s',
            'settings.*.closing_time' => 'required|date_format:H:i:s|after:settings.*.opening_time',
            'settings.*.max_capacity_per_hour' => 'required|integer|min:1|max:100',
            'settings.*.is_open' => 'required|boolean',
        ]);

        foreach ($request->settings as $settingData) {
            $setting = ReservationSetting::find($settingData['id']);
            $setting->update([
                'opening_time' => $settingData['opening_time'],
                'closing_time' => $settingData['closing_time'],
                'max_capacity_per_hour' => $settingData['max_capacity_per_hour'],
                'is_open' => $settingData['is_open'],
            ]);
        }

        return redirect()->back()->with('success', 'Reservation settings updated successfully!');
    }

    public function apiIndex()
    {
        $settings = ReservationSetting::all();
        return response()->json($settings);
    }
}

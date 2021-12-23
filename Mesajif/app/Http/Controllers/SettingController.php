<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function settings()
    {
        return Settings::first();
    }
    public function editsettings(Request $request)
    {
        $setting = Settings::first();
        $setting->title = $request->title;
        $setting->keywords = $request->keywords;
        if ($request->file('image')) {
            $setting->image = $request->file('image')->store('logo', 'public');
        }
        $setting->description = $request->description;
        $setting->address = $request->address;
        $setting->phone = $request->phone;
        $setting->facebook = $request->facebook;
        $setting->instagram = $request->instagram;
        $setting->youtube = $request->youtube;
        $setting->email = $request->email;
        $setting->save();

        return Settings::first();
    }
}

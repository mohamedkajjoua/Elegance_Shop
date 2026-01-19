<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\admin\SettingService;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }


    public function index()
    {

        $settings = Setting::first() ?? new Setting();
        return response()->json($settings);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'site_name'         => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'call_us'          => 'nullable|string',
            'email_us'         => 'nullable|email',
            'visit_us'         => 'nullable|string',
            'instagram'        => 'nullable|string',
            'facebook'         => 'nullable|string',
            'x'                => 'nullable|string',
        ]);


        $setting = Setting::updateOrCreate(
            ['id' => 1],
            $data
        );

        return response()->json($setting, 200);
    }
}

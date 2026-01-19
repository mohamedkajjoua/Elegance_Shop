<?php

namespace App\Services\admin;

use App\Models\Setting;

class SettingService
{

    public function getSettings()
    {

        return Setting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Elegance Shop',
                'email_us' => 'admin@example.com'
            ]
        );
    }


    public function updateSettings(array $data)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($data);

        return $setting;
    }


    public function find($id)
    {
        return Setting::findOrFail($id);
    }
}

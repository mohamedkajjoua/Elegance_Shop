<?php
namespace App\Services\admin;

use App\Models\Setting;


class SettingService
{
    public function all()
    {
        return Setting::all();
    }

    public function find($id)
    {
        return Setting::findOrFail($id);
    }

    public function create(array $data)
    {
        return Setting::create($data);
    }

    public function update($id, array $data)
    {
        $setting = Setting::findOrFail($id);
        $setting->update($data);
        return $setting;
    }

    public function delete($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return true;
    }
}
  

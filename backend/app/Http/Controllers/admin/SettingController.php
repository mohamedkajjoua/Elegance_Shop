<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\admin\SettingService;



class SettingController extends Controller
{
    //
     protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        return response()->json($this->settingService->all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sitename' => 'required|string',
            'site_description' => 'nullable|string',
            'call_us' => 'nullable|string',
            'email_us' => 'nullable|email',
            'visit_us' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'x' => 'nullable|string',
        ]);

        $setting = $this->settingService->create($data);

        return response()->json($setting, 201);
    }

    public function show($id)
    {
        return response()->json($this->settingService->find($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'sitename' => 'required|string',
            'site_description' => 'nullable|string',
            'call_us' => 'nullable|string',
            'email_us' => 'nullable|email',
            'visit_us' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'x' => 'nullable|string',
        ]);

        $setting = $this->settingService->update($id, $data);

        return response()->json($setting);
    }

    public function destroy($id)
    {
        $this->settingService->delete($id);
        return response()->json(['message' => 'Deleted successfully']);
    }


}

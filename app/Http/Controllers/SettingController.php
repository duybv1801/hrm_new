<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $settings = $this->settingService->getAllSettings();
        return view('setting.setting', compact('settings'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->except('_token');
        $this->settingService->updateSettings($data);

        return redirect()->route('settings.index')->with('success', trans('validation.crud.updated'));
    }
}

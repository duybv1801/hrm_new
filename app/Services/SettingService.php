<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings()
    {
        return $this->settingRepository->getAllSettings();
    }

    public function updateSettings(array $data)
    {
        $settingsMap = $this->settingRepository->all()->keyBy('key');

        foreach ($data as $key => $value) {
            if ($settingsMap->has($key)) {
                $setting = $settingsMap->get($key);
                $this->settingRepository->updateValue($setting, $value);
            } else {
                $this->settingRepository->create(['key' => $key, 'value' => $value]);
            }
        }
    }
}

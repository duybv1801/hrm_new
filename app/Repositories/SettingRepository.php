<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\BaseRepository;

/**
 * Class SettingRepository
 * @package App\Repositories
 */

class SettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setting::class;
    }

    public function getAllSettings()
    {
        return Setting::select('key', 'value')->pluck('value', 'key')->toArray();
    }

    public function searchByConditions($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->get();
    }

    public function findByKey($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function updateValue(Setting $setting, $value)
    {
        $setting->value = $value;
        $setting->save();
    }

    public function getCoefficients()
    {
        $keys = [
            'day_time_ot',
            'night_time_ot',
            'ot_day_dayoff',
            'ot_night_dayoff',
            'ot_day_holiday',
            'ot_night_holiday',
        ];

        $coefficients = $this->model
            ->whereIn('key', $keys)
            ->pluck('value', 'key');

        return $coefficients;
    }

    public function otApproveSetting()
    {
        $keys = [
            'ot_approve',
            'total_ot_time',
            'ot_registration_time',
        ];
        $otApproveSettings = $this->model
            ->whereIn('key', $keys)->pluck('value', 'key');

        return $otApproveSettings;
    }

    public function getTimeLunch()
    {
        $keys = [
            'check_in_time',
            'check_out_time',
            'lunch_time_start',
            'lunch_time_end',
            'working_time',
            'max_working_minutes_everyday_day',
        ];
        $timeLunch = $this->model->whereIn('key', $keys)->pluck('value', 'key');
        return $timeLunch;
    }
}

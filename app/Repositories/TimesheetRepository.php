<?php

namespace App\Repositories;

use App\Models\Timesheet;
use App\Models\User;
use App\Models\Setting;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\TimesheetUpdate;

/**
 * Class TimesheetRepository
 * @package App\Repositories
 */

class TimesheetRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'record_date',

    ];

    public function model()
    {
        return Timesheet::class;
    }
    /**
     * Get searchable fields array
     * @return array
     */
    public function getFieldsSearchable()
    {
        $this->fieldSearchable;
    }

    public function searchByConditions($search, $userIds = [])
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '<=', $endDate);
                        break;
                    case 'user_ids':
                        $query = $query->whereIn('user_id', $value);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }
        if ($userIds != null) {
            $query = $query->whereIn('user_id', $userIds);
        }
        $query = $query->where(function ($q) {
            $q->where(function ($subQuery) {
                $subQuery->where('overtime_hours', '>', 0)
                    ->orWhereRaw('DAYOFWEEK(record_date) NOT IN (1, 7)'); // 1->Sunday, 7->Saturday
            });
        });
        return $query->with('user')->orderBy('record_date', 'DESC')->paginate(config('define.paginate'));
    }

    public function getWorkingHours($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '<=', $endDate);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }
        $setting = Setting::where('key', 'working_time')->pluck('value', 'key');

        $result = $query->get()->sum(function ($timesheet) use ($setting) {
            return  min((int) $setting['working_time'], $timesheet->working_hours 
                + round($timesheet->leave_hours / config('define.hour'), config('define.decimal')))
                + round($timesheet->overtime_hours / config('define.hour'), config('define.decimal'));
        });

        return $result;
    }

    public function findByConditions($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $query = $query->where('record_date', '>=', $value);
                        break;
                    case 'end_date':
                        $query = $query->where('record_date', '<=', $value);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->with('user')->orderBy('record_date', 'DESC')->first();
    }

    public function createTimesheet($importData)
    {
        $newTimesheets = [];
        $userIds = User::pluck('id')->toArray();
        foreach ($importData as $key => $data) {
            $userId = $data[config('define.home.userId')];
            if (!in_array($userId, $userIds)) {
                unset($importData[$key]);
            } else {
                $recordDate = Carbon::parse($data[config('define.home.recordDate')])->format(config('define.date_search'));
                $newTimesheets[] = [
                    'userId' => $userId,
                    'recordDate' => $recordDate,
                ];
            }
        }
        $existingTimesheets = Timesheet::whereIn('user_id', array_column($newTimesheets, 'userId'))
            ->whereIn('record_date', array_column($newTimesheets, 'recordDate'))
            ->get();
        foreach ($importData as $data) {
            $userId = $data[config('define.home.userId')];
            $recordDate = Carbon::parse($data[config('define.home.recordDate')])->format(config('define.date_search'));
            $key = $userId . $recordDate;
            $existingTimesheet = $existingTimesheets->first(function ($item) use ($key) {
                return $item->user_id . $item->record_date == $key;
            });
            if ($existingTimesheet) {
                $updateData = [
                    'in_time' => $data[config('define.home.inTime')],
                ];

                if (isset($data[config('define.home.outTime')])) {
                    $updateData['out_time'] = $data[config('define.home.outTime')];
                }
                $existingTimesheet->update($updateData);
                event(new TimesheetUpdate($existingTimesheet));
            } else {
                $createData = [
                    'user_id' => $data[config('define.home.userId')],
                    'record_date' => $data[config('define.home.recordDate')],
                    'in_time' => $data[config('define.home.inTime')],
                    'check_in' => $data[config('define.home.inTime')],
                ];
                if (isset($data[config('define.home.outTime')]) && $data[config('define.home.outTime')] != null) {
                    $createData['out_time'] = $data[config('define.home.outTime')];
                    $createData['check_out'] = $data[config('define.home.outTime')];
                }
                $timesheet = $this->model->create($createData);
                event(new TimesheetUpdate($timesheet));
            }
        }
    }

    public function updateOT($overtime)
    {
        $recordDate = Carbon::parse($overtime->to_datetime)->format(config('define.date_search'));
        $data['user_id'] = $overtime->user_id;
        $data['record_date'] = $recordDate;
        $data['overtime_hours'] = $overtime->salary_hours;
        $existingTimesheet = Timesheet::where('user_id', $data['user_id'])
            ->where('record_date', $data['record_date'])->first();
        if ($existingTimesheet) {
            $existingTimesheet->update($data);
        } else {
            $this->model->create($data);
        }
    }

    public function updateLeave($leave)
    {
        $recordDate = Carbon::parse($leave->to_datetime)->format(config('define.date_search'));
        $data['user_id'] = $leave->user_id;
        $data['record_date'] = $recordDate;
        $data['leave_hours'] = $leave->total_hours;
        $existingTimesheet = Timesheet::where('user_id', $data['user_id'])
            ->where('record_date', $data['record_date'])->first();
        if ($existingTimesheet) {
            $existingTimesheet->update($data);
        } else {
            $this->model->create($data);
        }
    }

    public function checkRemoteCheckIn($userId, $date)
    {
        $check = true;
        $query = $this->model->where('user_id', $userId)
            ->where('record_date', 'like', '%' . $date . '%')
            ->whereNotNull('check_in');
        if ($query->exists()) {
            $check = false;
        }
        return $check;
    }

    public function calculateHours($search)
    {
        $query = $this->model;

        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '<=', $endDate);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        $leaveHours = $query->get()->sum(function ($timesheet) {
            return round($timesheet->leave_hours / config('define.hour'), config('define.decimal'));
        });

        $otHours = $query->get()->sum(function ($timesheet) {
            return round($timesheet->overtime_hours / config('define.hour'), config('define.decimal'));
        });

        $workHours = $query->get()->sum(function ($timesheet) {
            return round($timesheet->working_hours / config('define.hour'), config('define.decimal'));
        });

        return [
            'leaveHours' => $leaveHours,
            'otHours' => $otHours,
            'workHours' => $workHours,
        ];
    }
    public function getRecordDate()
    {
        return $this->model->pluck('record_date')->toArray();
    }
    public function getEmployeesByCodes(array $codes)
    {
        return $this->model->whereIn('code', $codes)->get();
    }
}

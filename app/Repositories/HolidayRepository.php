<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

/**
 * Class HolidayRepository
 * @package App\Repositories
 */

class HolidayRepository extends BaseRepository
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

    public function getHolidays()
    {
        return Holiday::orderBy('date', 'asc')->get();
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Holiday::class;
    }


    public function searchByConditions($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $start_date = now()->startOfYear();
        } else {
            $start_date = Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.date_search'));
        }
        if (!isset($search['end_date'])) {
            $end_date = now()->endOfYear();
        } else {
            $end_date = Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.date_search'));
        }

        if (isset($search['query'])) {
            $query = $query->where('title', 'like', '%' . $search['query'] . '%');
        }

        if (isset($search['sort_by']) && in_array($search['sort_by'], ['asc', 'desc'])) {
            $sortField = isset($search['order_by']) ? $search['order_by'] : 'date';
            $query = $query->orderBy($sortField, $search['sort_by']);
        }
        $query = $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
        return $query;
    }

    public function createHoliday(array $holidayData)
    {
        $existingDates = $this->model->whereIn('date', array_column($holidayData, 'date'))->pluck('date')->toArray();
        //can optimize
        $toCreate = [];
        $toUpdate = [];

        foreach ($holidayData as $data) {
            $date = Carbon::parse($data['date']);
            $found = false;
            foreach ($existingDates as $existingDate) {
                if ($date->isSameDay($existingDate)) {
                    $toUpdate[] = $data;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $toCreate[] = $data;
            }
        }
        foreach ($toCreate as $data) {
            $this->model->create([
                'date' => $data['date'],
                'title' => $data['title'],
            ]);
        }
        foreach ($toUpdate as $data) {
            $this->model->where('date', $data['date'])->update([
                'title' => $data['title'],
            ]);
        }
    }
}

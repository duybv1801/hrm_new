<?php

namespace App\Repositories;

use App\Models\Salary;
use Illuminate\Support\Carbon;

class SalaryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id', 'time', 'gross', 'tax', 'insurance',
        'advance_payment', 'reward', 'NET'
    ];
    /**
     * @inheritDoc
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * @inheritDoc
     */
    public function model()
    {
        return Salary::class;
    }

    public function searchByConditions($search, $userIds = [])
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'time':
                        $query = $query->where('time', $value);
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

        return $query->with('user')->paginate(config('define.paginate'));
    }
}

<?php

namespace App\Repositories;

use App\Models\AdvancePayment;
use Illuminate\Support\Carbon;

class AdvancePaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id', 'time', 'reason', 'status',
        'payments', 'money', 'bank', 'account_number'
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
        return AdvancePayment::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('time', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('time', '<=', $endDate);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->with('user')->orderBy('status', 'ASC')->paginate(config('define.paginate'));
    }
}

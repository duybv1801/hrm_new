<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Leave;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class RemoteReponsitory
 * @package App\Repositories
 */

class LeaveRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'from_datetime',
        'to_datetime',
        'type',
        'total_hours',
        'reason',
        'evident',
        'approver_id',
        'comment',
        'status',
    ];

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
        return Leave::class;
    }
    public function findByUserId($userId)
    {
        return $this->model->where('user_id', $userId);
    }

    public function searchByConditionsLeave($search)
    {
        $query = $this->model->where('user_id', Auth::user()->id);

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }
    public function searchByConditions($search)
    {
        $query = $this->model->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }
    public function searchByConditionPO($search)
    {
        $query = $this->model->where('approver_id', Auth::user()->id);

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }

    private function applySearchConditions($query, $search)
    {
        $startDate = isset($search['startDate']) ? Carbon::createFromFormat(config('define.date_show'), $search['startDate'])->format(config('define.datetime_db')) : now()->startOfYear()->format(config('define.date_search'));
        $endDate = isset($search['endDate']) ? Carbon::createFromFormat(config('define.date_show'), $search['endDate'])->format(config('define.datetime_db')) : now()->endOfYear()->format(config('define.date_search'));

        $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC')
            ->where('from_datetime', '>=', $startDate)
            ->where('to_datetime', '<=', $endDate);

        if (isset($search['query'])) {
            $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('code', 'like', '%' . $search['query'] . '%');
            });
        }

        return $query;
    }
}

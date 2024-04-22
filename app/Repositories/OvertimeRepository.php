<?php

namespace App\Repositories;

use App\Models\Overtime;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

/**
 * Class OvertimeRepository
 * @package App\Repositories
 * @version August 30, 2023, 3:07 am UTC
 */

class OvertimeRepository extends BaseRepository
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
        return Overtime::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model;
        if (!isset($search['startDate'])) {
            $startDate = now()->startOfMonth();
        } else {
            $startDate = Carbon::createFromFormat(config('define.date_show'), $search['startDate'])->format(config('define.date_search'));
        }
        if (!isset($search['endDate'])) {
            $endDate = now()->endOfMonth();
        } else {
            $endDate = Carbon::createFromFormat(config('define.date_show'), $search['endDate'])->format(config('define.date_search'));
        }
        if (isset($search['query'])) {
            $query = $query->where('title', 'like', '%' . $search['query'] . '%');
        }
        $query = $query->where('from_datetime', '>=', $startDate)->where('to_datetime', '<=', $endDate);

        if (isset($search['sort']) && $search['column']) {
            $query->orderBy($search['column'], $search['sort']);
        } elseif (isset($search['order'])) {
            $query = $query->orderByRaw('FIELD(status, ' . implode(',', $search['order']) . ')')
                ->orderBy('created_at', 'DESC');
        } else {
            $query = $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
        }
        $query = $query->with('approver:id,code');

        return $query;
    }

    public function poQuery($query, $id)
    {
        $query = $query->where('approver_id', $id);
        return $query;
    }

    public function userQuery($query, $id)
    {
        $query = $query->where('user_id', $id)->paginate(config('define.paginate'));
        return $query;
    }
}

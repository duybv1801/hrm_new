<?php

namespace App\Repositories;

use App\Models\InOutForget;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

/**
 * Class InOutForgetRepository
 * @package App\Repositories
 */

class InOutForgetRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',

    ];

    public function model()
    {
        return InOutForget::class;
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
                        $query = $query->where('date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('date', '<=', $endDate);
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

        return $query->with('user')->orderBy('status', 'ASC')->paginate(config('define.paginate'));
    }
}

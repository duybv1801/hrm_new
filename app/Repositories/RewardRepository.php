<?php

namespace App\Repositories;

use App\Models\Reward;
use Illuminate\Support\Carbon;

class RewardRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id', 'time', 'reason', 'money',
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
        return Reward::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model->with('user');

        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'time':
                        $query = $query->where('time', '=', $value);
                        break;
                    case 'query':
                        $query = $query->whereHas('user', function ($q) use ($value) {
                            $q->where('name', 'like', '%' . $value . '%');
                        });
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->paginate(config('define.paginate'));
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 */

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'code',
        'start_date',
        'official_start_date',
        'dependent_person',
        'gender',
        'contract',
        'birthday',
        'phone',
        'status',
        'position',
        'user_id',
        'avatar',
        'role_id',
        'team_id',
        'leave_hours_left',
        'leave_hours_left_in_month',
        'account_number',
        'base_salary',
        'allowance',
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
        return User::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model;
        if (isset($search['query'])) {
            $query = $query->where('code', 'like', '%' . $search['query'] . '%');
        }
        $query = $query->orderBy('id');
        return $query->paginate(config('define.paginate'));
    }

    public function getUsersByPosition($roleId)
    {
        return $this->model->where('role_id', $roleId)->get();
    }
    public function getEmailsByPosition($emailId)
    {
        return $this->model->find($emailId);
    }
    public function getCodes()
    {
        return $this->model->pluck('code');
    }
    public function getEmailsByUserIds($ccIds)
    {
        return $this->model->whereIn('code', $ccIds)->pluck('email');
    }
    public function getRoleById($roleId)
    {
        return Role::where('id', $roleId)->first();
    }
    public function getUserByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }
    public function getAllUserByCode($code)
    {
        return $this->model->whereIn('code', $code)->get();
    }

    public function getUserCalSalary($ids = null, $start, $end, $time): array
    {
        $start = $start->format('Y-m-d');
        $end = $end->format('Y-m-d');

        $query = $this->model->with([
            'timesheets' => function ($query) use ($start, $end) {
                $query->whereBetween('record_date', [$start, $end])
                    ->select('user_id', 'working_hours', 'overtime_hours', 'leave_hours');
            },
            'advancePayment' => function ($query) use ($time) {
                $query->where('time', $time)
                    ->where('status', 2)
                    ->select('user_id', 'money');
            },
            'reward' => function ($query) use ($time) {
                $query->where('time', $time)
                    ->select('user_id')
                    ->selectRaw('SUM(money) as total_money')
                    ->groupBy('user_id');
            }
        ])
            ->select('id', 'name', 'base_salary', 'allowance', 'contract', 'dependent_person');

        if (!is_null($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get()->toArray();
    }

    public function getUserExportSalary($ids = null, $start, $end, $time)
    {
        $start = $start->format('Y-m-d');
        $end = $end->format('Y-m-d');
        $query = $this->model->with([
            'timesheets' => function ($query) use ($start, $end) {
                $query->whereBetween('record_date', [$start, $end])
                ->select('user_id', DB::raw('SUM(working_hours) as total_working_hours'), 
                DB::raw('SUM(overtime_hours) as total_overtime_hours'), 
                DB::raw('SUM(leave_hours) as total_leave_hours'))
                ->groupBy('user_id');
            },
            'salaries' => function ($query) use ($time) {
                $query->where('time', $time);
            },
            'roles'
        ])
            ->select('id', 'name','code','account_number', 'base_salary', 'allowance', 'contract', 'dependent_person');

        if (!is_null($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get()->toArray();
    }
}

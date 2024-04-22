<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

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
        'leave_hours_left_in_month'

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
        $query = $query->orderBy('created_at', 'DESC');
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
}

<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Models\Team;

class TeamRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'id',
        'name',
        'manager_id'
    ];
    protected $user;
    protected $team;

    public function __construct(User $user, Team $team)
    {
        $this->user = $user;
        $this->team = $team;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Team::class;
    }

    public function  getTeamInfo($userId)
    {
        $user = $this->user->find($userId);
        $manager = null;
        $teamIds = $user->teams->pluck('id')->toArray();
        
        $managers = [];
        if ($user->hasRole('po') || empty($teamIds) ) {
            $adminUsers = $this->user->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->select('id', 'code', 'email')->get();
            $managers = $adminUsers->toArray();
        } else {
            foreach ($teamIds as $teamId) {
                $team = $this->team->find($teamId);
                $members = $team->users()->select('users.id', 'users.code', 'users.email')->get();
                $managerId = $team->manager_id;
                $manager = $members->where('id', $managerId)->first();
                array_push($managers, $manager);
            }
        }
        $otherUsers = $this->user->select('id', 'code', 'email')
            ->where('id', '!=', $userId)
            ->get();
        $otherUsers = $otherUsers->toArray();
        $managerIds = array_column(array_filter($managers), 'id');
        $otherUsers = array_filter($otherUsers, function ($otherUser) use ($managerIds) {
            return !in_array($otherUser['id'], $managerIds);
        });

        return [
            'managers' => $managers,
            'otherUsers' => $otherUsers,
        ];
    }

    public function getMember($poId)
    {
        $team = Team::where('manager_id', $poId)->with('users')->first();
        $userData = $team->users;
        $filteredUserData = [];
        foreach ($userData as $user) {
            if ($user->id != $poId) {
                $filteredUserData[] = $user;
            }
        }
        $userIds = collect($filteredUserData)->pluck('id')->toArray();

        return [
            'userIds' => $userIds,
            'userData' => $filteredUserData
        ];
    }


    public function getTeamCc($userId)
    {
        $user = $this->user->find($userId);
        $teamIds = $user->teams->pluck('id')->toArray();
        $mailCc = [];

        foreach ($teamIds as $teamId) {
            $team = $this->team->find($teamId);
            $mails = $team->users()->pluck('email')->toArray();
            $mailCc = array_merge($mailCc, $mails);
        }
        $mailCc = array_unique($mailCc);

        return $mailCc;
    }

    public function getTeam()
    {
        return $this->team->pluck('manager');
    }
    public function getTeamList()
    {
        return $this->team->pluck('name', 'id');
    }
    public function findTeamById($id)
    {
        return Team::where('id', $id)->first();
    }
}

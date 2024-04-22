<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPermission;

class Remote extends Model
{
    use HasFactory, HasPermission;

    protected $fillable = [
        'user_id',
        'from_datetime',
        'to_datetime',
        'total_hours',
        'reason',
        'evident',
        'approver_id',
        'comment',
        'status',
        'cc'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getName()
    {
        $approver = User::find($this->user_id);

        return $approver->code;
    }
    public function getApprove()
    {
        $approver = User::find($this->approver_id);

        return $approver->code;
    }
}

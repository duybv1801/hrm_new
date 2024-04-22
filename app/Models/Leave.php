<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Leave
 * @package App\Models
 * @version March 11, 2023, 4:01 pm UTC
 *
 * @property integer $user_id
 * @property string|\Carbon\Carbon $from_datetime
 * @property string|\Carbon\Carbon $to_datetime
 */
class Leave extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'from_datetime',
        'to_datetime',
        'total_hours',
        'type',
        'reason',
        'evident',
        'approver_id',
        'comment',
        'status',
        'calculator_leave',
        'calculator_leave_in_month',
    ];

    public function users()
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
    public function getLeftLeave()
    {
        $leftLeave = User::find($this->user_id);

        return $leftLeave->leave_hours_left;
    }
    public function getLeftLeaveMonth()
    {
        $leftLeave = User::find($this->user_id);

        return $leftLeave->leave_hours_left_in_month;
    }
}

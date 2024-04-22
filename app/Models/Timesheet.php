<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'record_date',
        'in_time',
        'out_time',
        'check_in',
        'check_out',
        'status',
        'working_hours',
        'remote_hours',
        'overtime_hours',
        'leave_hours',
    ];
    protected $casts = [
        'user_id' => 'integer'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

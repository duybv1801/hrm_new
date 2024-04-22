<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InOutForget extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'in_time',
        'out_time',
        'total_hours',
        'reason',
        'evident',
        'approver_id',
        'comment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

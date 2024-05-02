<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'time', 'reason', 'status',
        'payments', 'money', 'bank', 'account_number'
    ];

//    protected $casts = [
//        'time'  => 'date:m-Y'
//    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

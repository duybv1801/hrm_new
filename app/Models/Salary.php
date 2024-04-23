<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id', 'time', 'gross', 'tax', 'insurance',
        'advance_payment', 'reward', 'NET'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

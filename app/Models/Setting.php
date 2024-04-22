<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasPermission;

class Setting extends Model
{
    use HasFactory, SoftDeletes, HasPermission;

    protected $fillable = [
        'key',
        'value'
    ];
}

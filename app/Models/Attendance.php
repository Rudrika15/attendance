<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
  protected $table = "attendances";
    protected $fillable = [
        'date',
        'user_id',
        'checkin',
        'checkout',
        'on_break',
        'off_break',
        'total_hours',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timelog extends Model
{
    //
    use HasFactory;
    protected $table = 'time_logs';
    protected $fillable = ['user_id', 'time_in', 'time_out'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

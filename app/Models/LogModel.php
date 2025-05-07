<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogModel extends Model
{
    use HasFactory;

    protected $table = 'logs'; 
    protected $fillable = [
        'level',
        'message',
        'context',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'user_id', 'content', 'updated_at', 'thread_date'
    ];

    // Automatically cast these attributes to Carbon instances
    protected $dates = ['updated_at', 'thread_date'];
}

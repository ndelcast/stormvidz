<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'person_image',
        'format',
        'speech',
        'accent',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $fillable = ['story_id', 'name'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
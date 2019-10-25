<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'title',
        'body',
        'author_id',
        'allowComments',
        'premiumPost',
        'weekNumber',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'author_id');
    }
}

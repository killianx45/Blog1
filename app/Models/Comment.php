<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use CrudTrait;
    use HasFactory;

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}

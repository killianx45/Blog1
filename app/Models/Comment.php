<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[ApiResource]
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

<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post as ApiPost;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new ApiPost(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class Comment extends Model
{
    use CrudTrait;
    use HasFactory;
    /**
     * Attributs cachés en fonction du rôle de l'utilisateur
     * 
     * @return array
     */

    protected $hidden = [];
    public function getHidden()
    {
        if (Auth::check() && Auth::user()->role === 'ROLE_ADMIN') {
            return [];
        }
        return ['id_user', 'author'];
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}

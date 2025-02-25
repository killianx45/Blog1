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
class Post extends Model
{
    use CrudTrait;
    use HasFactory;
    // Un post a plusieurs commentaires possibles (hasMany)
    // Fonction qui retourne tous les commentaires du post en question ($this)
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_post');
    }
    // Fonction qui retourne l'auteur du post en question ($this)
    // on peut dire que le post appartient à 1 auteur (belongsTo)
    public function author()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    protected $fillable = ['title', 'body', 'slug', 'id_user'];
}

<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ApiResource]
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

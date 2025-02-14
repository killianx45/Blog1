<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // Un post a plusieurs commentaires possibles (hasMany)
    // Fonction qui retourne tous les commentaires du post en question ($this)
    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'id_post');
    }
    // Fonction qui retourne l'auteur du post en question ($this)
    // on peut dire que le post appartient à 1 auteur (belongsTo)
    public function author()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'categorie_post');
    }

    protected $fillable = ['title', 'body', 'slug', 'id_user'];
}

<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ApiResource]
class Category extends Model
{
    use CrudTrait;
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    protected $fillable = ['name', 'slug'];
}

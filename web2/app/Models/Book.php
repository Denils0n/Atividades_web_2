<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Campos que podem ser atribuídos em massa
    protected $fillable = ['title', 'author_id', 'publisher_id', 'published_year'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}



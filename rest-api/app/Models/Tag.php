<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $cascadeDeletes = ['blogs', 'pages'];
    protected $dates = ['deleted_at'];

    public function blogs()
    {
        return $this->morphedByMany(Blog::class,
            'taggable');
    }

    public function pages()
    {
        return $this->morphedByMany(Page::class,
            'taggable');
    }
}

Relation::morphMap([
    'blogs' => Blog::class,
    'pages' => Page::class
]);

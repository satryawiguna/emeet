<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'message'
    ];

    protected $dates = ['deleted_at'];

    public function blog()
    {
        return $this->morphTo(Blog::class);
    }

    public function page()
    {
        return $this->morphTo(Page::class);
    }
}

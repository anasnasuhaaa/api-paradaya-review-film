<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory, HasUuids;
    protected $table  = 'movie';
    protected $fillable = [
        'title',
        'poster',
        'summary',
        'genre_id',
        'year'
    ];
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }
    public function listReviews()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }
    public function listCast()
    {
        return $this->hasMany(CastMovie::class, 'movie_id');
    }
}

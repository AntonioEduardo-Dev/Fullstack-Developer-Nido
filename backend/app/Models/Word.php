<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'word'
    ];

    public function favorites()
    {
        return $this->hasMany(WordFavorite::class, 'word_id');
    }

    public function history()
    {
        return $this->hasMany(WordHistory::class, 'word_id');
    }
}

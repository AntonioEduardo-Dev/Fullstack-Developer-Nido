<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordFavorite extends Model
{
    use HasFactory;

    protected $table = 'word_favorites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'word_id'
    ];

    public function word()
    {
        return $this->belongsTo(Word::class, "word_id");
    }
}

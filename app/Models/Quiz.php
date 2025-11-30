<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'duration',
        'passing_score',
        'auto_check',
    ];

    protected $casts = [
        'auto_check' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getTotalPoints()
    {
        return $this->questions()->sum('points');
    }
}
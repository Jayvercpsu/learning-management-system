<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_points',
        'earned_points',
        'started_at',
        'submitted_at',
        'is_checked',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_checked' => 'boolean',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function calculateScore()
    {
        $this->earned_points = $this->answers()->sum('points_earned');
        if ($this->total_points > 0) {
            $this->score = ($this->earned_points / $this->total_points) * 100;
        }
        $this->save();
    }
}
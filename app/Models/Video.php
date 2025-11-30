<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'video_path',
        'thumbnail',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationFormatted()
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
<?php

namespace App\Models;

use App\Services\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        $url = app(MediaStorageService::class)->url($this->file_path);
        if (! $url) {
            return null;
        }

        $fileType = strtolower((string) $this->file_type);
        if ($fileType !== '') {
            $normalizedUrl = strtolower($url);
            $tmpSuffix = '.' . $fileType . '.tmp';

            if (str_ends_with($normalizedUrl, $tmpSuffix)) {
                return substr($url, 0, -4);
            }
        }

        return $url;
    }
}

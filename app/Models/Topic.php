<?php

namespace App\Models;

use App\Services\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    private const OFFICE_EXTENSIONS = [
        'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
    ];

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

    public static function officeExtensions(): array
    {
        return self::OFFICE_EXTENSIONS;
    }

    public function getIsOfficeFileAttribute(): bool
    {
        $extension = strtolower((string) $this->file_type);
        return in_array($extension, self::OFFICE_EXTENSIONS, true);
    }

    public function getOfficePreviewUrlAttribute(): ?string
    {
        if (! $this->is_office_file) {
            return null;
        }

        $sourceUrl = $this->sanitizedOfficePreviewSourceUrl();
        if (! $sourceUrl) {
            return null;
        }

        return 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($sourceUrl);
    }

    public function getOfficePreviewFallbackUrlAttribute(): ?string
    {
        if (! $this->is_office_file) {
            return null;
        }

        $sourceUrl = $this->sanitizedOfficePreviewSourceUrl();
        if (! $sourceUrl) {
            return null;
        }

        return 'https://docs.google.com/gview?embedded=1&url=' . rawurlencode($sourceUrl);
    }

    private function sanitizedOfficePreviewSourceUrl(): ?string
    {
        $url = $this->file_url;
        if (! is_string($url) || $url === '') {
            return null;
        }

        if (preg_match('/[\r\n]/', $url)) {
            return null;
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        if ($host === '' || $this->isPrivateOrLocalHost($host)) {
            return null;
        }

        return $url;
    }

    private function isPrivateOrLocalHost(string $host): bool
    {
        if ($host === 'localhost' || $host === '127.0.0.1' || $host === '::1') {
            return true;
        }

        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_match('/^(10\.|127\.|169\.254\.|192\.168\.|172\.(1[6-9]|2\d|3[0-1])\.)/', $host) === 1;
        }

        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return str_starts_with($host, 'fc')
                || str_starts_with($host, 'fd')
                || str_starts_with($host, 'fe80')
                || $host === '::1';
        }

        return false;
    }
}

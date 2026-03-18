<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof PostTooLargeException) {
            $isTopicUpload = $request->is('teacher/topics*');
            $field = $isTopicUpload ? 'file' : 'video';
            $configuredMaxKb = (int) config(
                $isTopicUpload ? 'media.topic_upload.max_kb' : 'media.video_upload.max_kb',
                $isTopicUpload ? 204800 : 512000
            );
            $effectiveMaxBytes = min(
                $configuredMaxKb * 1024,
                $this->phpUploadLimitBytes()
            );
            $maxSize = $this->formatBytesToMb($effectiveMaxBytes);
            $phpUploadMax = (string) ini_get('upload_max_filesize');
            $phpPostMax = (string) ini_get('post_max_size');
            $helpText = " Current PHP limits are upload_max_filesize={$phpUploadMax} and post_max_size={$phpPostMax}.";
            if (app()->environment('local')) {
                $helpText .= ' If you are using `php artisan serve`, restart with `php -d upload_max_filesize=512M -d post_max_size=512M artisan serve`.';
            }

            Log::warning('PostTooLargeException caught', [
                'path' => $request->path(),
                'field' => $field,
                'content_length' => $request->server('CONTENT_LENGTH'),
                'configured_max_kb' => $configuredMaxKb,
                'php_upload_max_filesize' => $phpUploadMax,
                'php_post_max_size' => $phpPostMax,
                'effective_max_mb' => $maxSize,
                'user_id' => optional($request->user())->id,
            ]);

            return back()->withErrors([
                $field => "The file is too large. Maximum upload size is {$maxSize}. Please check your file size and try again.{$helpText}"
            ])->withInput();
        }

        return parent::render($request, $exception);
    }

    private function phpUploadLimitBytes(): int
    {
        $uploadMax = $this->iniSizeToBytes((string) ini_get('upload_max_filesize'));
        $postMax = $this->iniSizeToBytes((string) ini_get('post_max_size'));

        if ($uploadMax === 0 && $postMax === 0) {
            return PHP_INT_MAX;
        }

        if ($uploadMax === 0) {
            return $postMax;
        }

        if ($postMax === 0) {
            return $uploadMax;
        }

        return min($uploadMax, $postMax);
    }

    private function iniSizeToBytes(string $size): int
    {
        $value = trim(strtolower($size));
        if ($value === '' || $value === '-1') {
            return 0;
        }

        $unit = substr($value, -1);
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) round($number * 1024 * 1024 * 1024),
            'm' => (int) round($number * 1024 * 1024),
            'k' => (int) round($number * 1024),
            default => (int) round($number),
        };
    }

    private function formatBytesToMb(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0MB';
        }

        $megabytes = $bytes / (1024 * 1024);

        if ($megabytes >= 10) {
            return (string) round($megabytes) . 'MB';
        }

        return number_format($megabytes, 1) . 'MB';
    }
}

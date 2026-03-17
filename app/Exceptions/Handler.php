<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
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
            $maxSize = $isTopicUpload ? '100MB' : '500MB';

            return back()->withErrors([
                $field => "The file is too large. Maximum upload size is {$maxSize}. Please check your file size and try again."
            ])->withInput();
        }

        return parent::render($request, $exception);
    }
}

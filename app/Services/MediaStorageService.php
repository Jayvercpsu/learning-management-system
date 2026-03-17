<?php

namespace App\Services;

use App\Services\Cloudinary\CustomUploadApi;
use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class MediaStorageService
{
    private ?Cloudinary $cloudinary = null;
    private ?CustomUploadApi $uploadApi = null;

    public function store(UploadedFile $file, string $folder, ?string $filename = null): string
    {
        if ($this->useCloudinary()) {
            return $this->storeInCloudinary($file, $folder, $filename);
        }

        return $this->storeLocally($file, $folder, $filename);
    }

    public function delete(?string $storedValue): void
    {
        if (blank($storedValue)) {
            return;
        }

        if ($this->isCloudinaryUrl((string) $storedValue)) {
            $this->deleteFromCloudinary((string) $storedValue);
            return;
        }

        Storage::disk($this->localDisk())->delete((string) $storedValue);
    }

    public function url(?string $storedValue): ?string
    {
        if (blank($storedValue)) {
            return null;
        }

        if ($this->isRemotePath((string) $storedValue)) {
            return $this->normalizeLocalStorageUrl((string) $storedValue);
        }

        $url = Storage::disk($this->localDisk())->url((string) $storedValue);
        $requestBasedUrl = $this->localStorageUrlFromCurrentRequest($url);
        if ($requestBasedUrl !== null) {
            return $requestBasedUrl;
        }

        return $this->normalizeLocalStorageUrl($url);
    }

    public function isRemotePath(?string $storedValue): bool
    {
        if (blank($storedValue)) {
            return false;
        }

        return filter_var($storedValue, FILTER_VALIDATE_URL) !== false;
    }

    public function localAbsolutePath(?string $storedValue): ?string
    {
        if (blank($storedValue) || $this->isRemotePath($storedValue)) {
            return null;
        }

        return Storage::disk($this->localDisk())->path((string) $storedValue);
    }

    private function storeLocally(UploadedFile $file, string $folder, ?string $filename): string
    {
        if ($filename !== null) {
            return $file->storeAs($folder, $filename, $this->localDisk());
        }

        return $file->store($folder, $this->localDisk());
    }

    private function storeInCloudinary(UploadedFile $file, string $folder, ?string $filename): string
    {
        $resourceType = $this->detectResourceType($file);

        $uploadOptions = [
            'resource_type' => $resourceType,
            'folder' => $this->cloudinaryFolder($folder),
            'type' => 'upload',
            'overwrite' => false,
            'unique_filename' => true,
            // Prevent Cloudinary raw uploads from inheriting PHP temp-file names like *.tmp.
            'filename' => $filename ?: $file->getClientOriginalName(),
        ];

        if ($filename !== null) {
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $publicId = Str::slug($baseName) ?: ('file-' . Str::lower(Str::random(8)));

            $uploadOptions['public_id'] = $publicId;
            $uploadOptions['unique_filename'] = false;
        } else {
            $uploadOptions['use_filename'] = true;
        }

        $fileSize = (int) ($file->getSize() ?? 0);
        $chunkSizeBytes = (int) config('media.cloudinary.chunk_size_bytes', 6291456);
        if ($fileSize > 10 * 1024 * 1024 && $chunkSizeBytes > 0) {
            // Force smaller multipart chunks for large uploads to avoid provider-side per-request limits.
            $uploadOptions['chunk_size'] = $chunkSizeBytes;
        }

        Log::info('Cloudinary upload start', [
            'folder' => $folder,
            'resource_type' => $resourceType,
            'original_name' => $file->getClientOriginalName(),
            'target_filename' => $filename,
            'size_bytes' => $fileSize,
            'chunk_size' => $uploadOptions['chunk_size'] ?? null,
            'client_mime' => $file->getClientMimeType(),
            'detected_mime' => $file->getMimeType(),
        ]);

        try {
            $result = $this->uploadApi()->upload($file->getRealPath(), $uploadOptions);
        } catch (Throwable $e) {
            Log::error('Cloudinary upload failed', [
                'folder' => $folder,
                'resource_type' => $resourceType,
                'original_name' => $file->getClientOriginalName(),
                'target_filename' => $filename,
                'size_bytes' => $file->getSize(),
                'message' => $e->getMessage(),
            ]);

            if (str_contains($e->getMessage(), 'cURL error 60')) {
                throw new RuntimeException(
                    'Cloudinary SSL verification failed. For local WAMP only, set CLOUDINARY_VERIFY_SSL=false or set CLOUDINARY_CA_BUNDLE to a valid CA bundle path.'
                );
            }

            throw $e;
        }
        $secureUrl = (string) ($result['secure_url'] ?? $result['url'] ?? '');

        if ($secureUrl === '') {
            throw new RuntimeException('Cloudinary upload failed: missing uploaded file URL.');
        }

        Log::info('Cloudinary upload success', [
            'folder' => $folder,
            'resource_type' => $resourceType,
            'original_name' => $file->getClientOriginalName(),
            'secure_url' => $secureUrl,
        ]);

        return $secureUrl;
    }

    private function deleteFromCloudinary(string $url): void
    {
        $assetData = $this->extractCloudinaryAssetData($url);
        if ($assetData === null) {
            return;
        }

        $uploadApi = $this->uploadApi();
        $publicId = $assetData['public_id'];
        $resourceType = $assetData['resource_type'];
        $resourceTypeCandidates = $resourceType ? [$resourceType] : ['image', 'video', 'raw'];
        $publicIdCandidates = [$publicId];

        $publicIdWithoutExtension = preg_replace('/\.[^.]+$/', '', $publicId);
        if ($publicIdWithoutExtension !== null && $publicIdWithoutExtension !== $publicId) {
            $publicIdCandidates[] = $publicIdWithoutExtension;
        }

        foreach ($resourceTypeCandidates as $candidate) {
            foreach ($publicIdCandidates as $publicIdCandidate) {
                try {
                    $response = $uploadApi->destroy($publicIdCandidate, [
                        'resource_type' => $candidate,
                        'type' => 'upload',
                        'invalidate' => true,
                    ]);

                    $result = strtolower((string) ($response['result'] ?? ''));
                    if (in_array($result, ['ok', 'not found'], true)) {
                        return;
                    }
                } catch (Throwable) {
                    // Try the next resource type/public ID candidate.
                }
            }
        }
    }

    private function extractCloudinaryAssetData(string $url): ?array
    {
        $parsedUrl = parse_url($url);
        $path = trim((string) ($parsedUrl['path'] ?? ''), '/');
        if ($path === '') {
            return null;
        }

        $segments = array_values(array_filter(explode('/', $path)));
        $uploadIndex = array_search('upload', $segments, true);
        if ($uploadIndex === false || $uploadIndex + 1 >= count($segments)) {
            return null;
        }

        $resourceType = $segments[$uploadIndex - 1] ?? null;
        if (!in_array($resourceType, ['image', 'video', 'raw'], true)) {
            $resourceType = null;
        }

        $publicIdSegments = array_slice($segments, $uploadIndex + 1);
        if (empty($publicIdSegments)) {
            return null;
        }

        if (Str::startsWith($publicIdSegments[0], 's--')) {
            array_shift($publicIdSegments);
        }

        if (!empty($publicIdSegments)) {
            $versionIndex = null;
            foreach ($publicIdSegments as $index => $segment) {
                if (preg_match('/^v\d+$/', $segment)) {
                    $versionIndex = $index;
                    break;
                }
            }

            if ($versionIndex !== null) {
                $publicIdSegments = array_slice($publicIdSegments, $versionIndex + 1);
            }
        }

        if (empty($publicIdSegments)) {
            return null;
        }

        $lastIndex = count($publicIdSegments) - 1;
        if (($resourceType ?? 'image') !== 'raw') {
            $publicIdSegments[$lastIndex] = preg_replace('/\.[^.]+$/', '', $publicIdSegments[$lastIndex]) ?? $publicIdSegments[$lastIndex];
        }

        $publicId = implode('/', $publicIdSegments);
        if ($publicId === '') {
            return null;
        }

        return [
            'public_id' => $publicId,
            'resource_type' => $resourceType,
        ];
    }

    private function isCloudinaryUrl(string $value): bool
    {
        if (! $this->isRemotePath($value)) {
            return false;
        }

        $host = strtolower((string) parse_url($value, PHP_URL_HOST));
        if ($host === '') {
            return false;
        }

        $configuredCname = strtolower((string) config('media.cloudinary.cname', ''));
        if ($configuredCname !== '' && $host === $configuredCname) {
            return true;
        }

        return str_contains($host, 'res.cloudinary.com');
    }

    private function cloudinaryFolder(string $folder): string
    {
        $baseFolder = trim((string) config('media.cloudinary.folder', 'LMS-ASSETS'), '/');
        $targetFolder = trim($folder, '/');

        if ($baseFolder === '') {
            return $targetFolder;
        }

        if ($targetFolder === '') {
            return $baseFolder;
        }

        return $baseFolder . '/' . $targetFolder;
    }

    private function cloudinary(): Cloudinary
    {
        if ($this->cloudinary instanceof Cloudinary) {
            return $this->cloudinary;
        }

        $urlConfig = (string) config('media.cloudinary.url', '');
        if ($urlConfig !== '') {
            $this->cloudinary = new Cloudinary($urlConfig);
            return $this->cloudinary;
        }

        $cloudName = (string) config('media.cloudinary.cloud_name', '');
        $apiKey = (string) config('media.cloudinary.api_key', '');
        $apiSecret = (string) config('media.cloudinary.api_secret', '');

        if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
            throw new RuntimeException('Cloudinary is enabled but credentials are missing. Check your .env values.');
        }

        $urlOptions = [
            'secure' => (bool) config('media.cloudinary.secure', true),
        ];

        $cname = (string) config('media.cloudinary.cname', '');
        if ($cname !== '') {
            $urlOptions['cname'] = $cname;
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => $urlOptions,
        ]);

        return $this->cloudinary;
    }

    private function uploadApi(): CustomUploadApi
    {
        if ($this->uploadApi instanceof CustomUploadApi) {
            return $this->uploadApi;
        }

        $this->uploadApi = new CustomUploadApi(
            $this->cloudinary()->configuration,
            $this->cloudinaryHttpOptions()
        );

        return $this->uploadApi;
    }

    private function cloudinaryHttpOptions(): array
    {
        $caBundle = trim((string) config('media.cloudinary.ca_bundle', ''));
        if ($caBundle !== '') {
            return ['verify' => $caBundle];
        }

        return ['verify' => (bool) config('media.cloudinary.verify_ssl', true)];
    }

    private function localDisk(): string
    {
        return (string) config('media.local_disk', 'public');
    }

    private function useCloudinary(): bool
    {
        return (bool) config('media.use_cloudinary', false);
    }

    private function detectResourceType(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $imageExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'avif', 'heic', 'heif', 'tif', 'tiff',
        ];
        if (in_array($extension, $imageExtensions, true)) {
            return 'image';
        }

        $videoExtensions = [
            'mp4', 'mov', 'avi', 'wmv', 'webm', 'mkv', 'm4v',
        ];
        if (in_array($extension, $videoExtensions, true)) {
            return 'video';
        }

        return 'raw';
    }

    private function normalizeLocalStorageUrl(string $url): string
    {
        if (! $this->isRemotePath($url)) {
            return $url;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return $url;
        }

        $path = (string) ($parts['path'] ?? '');
        if ($path === '' || ! Str::startsWith($path, '/storage')) {
            return $url;
        }

        if (! app()->bound('request')) {
            return $url;
        }

        $request = request();
        if (! $request) {
            return $url;
        }

        $urlHost = strtolower((string) ($parts['host'] ?? ''));
        $requestHost = strtolower((string) $request->getHost());
        if ($urlHost === '' || $requestHost === '') {
            return $url;
        }

        $urlScheme = strtolower((string) ($parts['scheme'] ?? 'http'));
        $urlPort = (int) ($parts['port'] ?? ($urlScheme === 'https' ? 443 : 80));
        $requestPort = (int) $request->getPort();

        $isUrlHostLocal = in_array($urlHost, ['localhost', '127.0.0.1', '::1'], true);
        $hostMismatch = $urlHost !== $requestHost;
        $portMismatch = $urlPort !== 0 && $requestPort !== 0 && $urlPort !== $requestPort;

        if (! $isUrlHostLocal || (! $hostMismatch && ! $portMismatch)) {
            return $url;
        }

        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return rtrim($request->getSchemeAndHttpHost(), '/') . $path . $query . $fragment;
    }

    private function localStorageUrlFromCurrentRequest(string $url): ?string
    {
        if (! app()->bound('request')) {
            return null;
        }

        $request = request();
        if (! $request) {
            return null;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $path = (string) ($parts['path'] ?? '');
        if ($path === '' || ! Str::startsWith($path, '/storage')) {
            return null;
        }

        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return rtrim($request->getSchemeAndHttpHost(), '/') . $path . $query . $fragment;
    }
}

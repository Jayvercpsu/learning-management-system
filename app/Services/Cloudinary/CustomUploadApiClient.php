<?php

namespace App\Services\Cloudinary;

use Cloudinary\Api\UploadApiClient as BaseUploadApiClient;

class CustomUploadApiClient extends BaseUploadApiClient
{
    public function __construct($configuration = null, private array $httpClientOverrides = [])
    {
        parent::__construct($configuration);
    }

    protected function buildHttpClientConfig(): array
    {
        $defaultConfig = parent::buildHttpClientConfig();

        if ($this->httpClientOverrides === []) {
            return $defaultConfig;
        }

        return array_replace_recursive($defaultConfig, $this->httpClientOverrides);
    }
}

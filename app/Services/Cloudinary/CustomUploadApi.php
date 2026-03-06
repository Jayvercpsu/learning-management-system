<?php

namespace App\Services\Cloudinary;

use Cloudinary\Api\Upload\UploadApi as BaseUploadApi;

class CustomUploadApi extends BaseUploadApi
{
    public function __construct($configuration = null, array $httpClientOverrides = [])
    {
        $this->apiClient = new CustomUploadApiClient($configuration, $httpClientOverrides);
    }
}

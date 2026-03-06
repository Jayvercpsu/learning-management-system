<?php

return [
    'use_cloudinary' => env('FILES_USE_CLOUDINARY', false),

    'local_disk' => env('MEDIA_LOCAL_DISK', 'public'),

    'cloudinary' => [
        'url' => env('CLOUDINARY_URL'),
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'secure' => env('CLOUDINARY_SECURE', true),
        'verify_ssl' => env('CLOUDINARY_VERIFY_SSL', true),
        'ca_bundle' => env('CLOUDINARY_CA_BUNDLE'),
        'cname' => env('CLOUDINARY_CNAME'),
        'folder' => env('CLOUDINARY_FOLDER', 'LMS-ASSETS'),
    ],
];

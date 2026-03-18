<?php

return [
    'use_cloudinary' => env('FILES_USE_CLOUDINARY', false),

    'local_disk' => env('MEDIA_LOCAL_DISK', 'public'),

    'topic_upload' => [
        'max_kb' => 204800, // 200 MB
        'extensions' => [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'jpg', 'jpeg', 'png', 'gif',
        ],
    ],

    'video_upload' => [
        'max_kb' => 512000, // 500 MB
        'extensions' => [
            'mp4', 'mov', 'avi', 'wmv',
        ],
    ],

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
        // Cloudinary may reject large raw chunks above 10MB on some plans.
        'chunk_size_bytes' => (int) env('CLOUDINARY_CHUNK_SIZE_BYTES', 6291456), // 6MB
        // When provider-side limits reject uploads, fall back to local disk storage.
        'fallback_to_local_on_failure' => env('CLOUDINARY_FALLBACK_TO_LOCAL_ON_FAILURE', true),
    ],
];

<?php

return [
    'access_key' => env('AWS_ACCESS_KEY_ID'),
    'secret_key' => env('AWS_SECRET_ACCESS_KEY'),

    's3' => [
        'region' => env('AWS_S3_REGION'),
        'bucket' => env('AWS_S3_BUCKET'),
        'ftp_url' => env('AWS_S3_FTP_URL'),
        'version' => env('AWS_S3_VERSION'),
    ],
];

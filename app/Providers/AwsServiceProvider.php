<?php

namespace App\Providers;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AwsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(S3Client::class, function (Application $app) {
            return new S3Client([
                'region' => config('aws.s3.region'),
                'version' => config('aws.s3.version'),
                'credentials' => CredentialProvider::fromCredentials(new Credentials(
                    config('aws.access_key'),
                    config('aws.secret_key'),
                )),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

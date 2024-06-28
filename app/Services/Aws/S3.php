<?php

namespace App\Services\Aws;

use App\Models\Image;
use App\Repository\ImageRepository;
use Aws\S3\S3Client;

class S3
{
    private const PRE_SIGNE_URL_DURATION = '30 minutes';

    public function __construct(
        private S3Client $client,
        private ImageRepository $imageRepository,
        private string $bucket = '',
    ){
        $this->bucket = config('aws.s3.bucket');
    }

    public function put(string $filename, string $file): string
    {
        $response = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SourceFile' => $file
        ]);

        return $response->get('ObjectURL');
    }

    public function delete(string $filename): bool
    {
        $response = $this->client->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);

        return $response->get('DeleteMarker');
    }

    public function deleteObjectsByIndex(string $index): bool
    {
        $objects = $this->imageRepository->findByCategoryName($index);

        $keys = $objects->map(function (Image $object) {
            return ['Key' => $object->image];
        })->all();

        $this->deleteObjects($keys);

        $existing = $this->listObjects($index);

        return count($existing) === 0;
    }

    public function deleteObjects(array $objects): void
    {
        $this->deleteObjects([
            'Bucket' => $this->bucket,
            'Delete' => [
                'Objects' => $objects
            ],
        ]);
    }

    public function listObjects(?string $index = ''): array
    {
        $args = [
            'Bucket' => $this->bucket,
        ];

        if($index === '') {
            $args['Prefix'] = $index;
        }

        $objects = $this->client->listObjectsV2($args);

        return array_map(function (array $content) {
            return $content['Key'];
        }, $objects->toArray());
    }

    public function singUrl(string $filename): string
    {
        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);

        $signedRequest = $this->client->createPresignedRequest($command, self::PRE_SIGNE_URL_DURATION);

        return sprintf('%s://%s%s?%s',
            $signedRequest->getUri()->getScheme(),
            $signedRequest->getUri()->getHost(),
            $signedRequest->getUri()->getPath(),
            $signedRequest->getUri()->getQuery()
        );
    }
}

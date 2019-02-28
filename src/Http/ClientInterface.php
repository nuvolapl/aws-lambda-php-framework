<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Http;

use Nuvola\AwsLambdaFramework\Exception\HttpException;

interface ClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws HttpException
     */
    public function get(string $urn, array &$headers): array;

    /**
     * {@inheritdoc}
     *
     * @throws HttpException
     */
    public function post(string $urn, string $content): void;
}

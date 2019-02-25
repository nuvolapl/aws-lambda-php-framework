<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Lambda;

class JsonResponse extends Response
{
    public function __construct(array $data = [], int $statusCode = 200, array $headers = [])
    {
        $body = \json_encode($data);

        parent::__construct(
            $body,
            $statusCode,
            \array_merge(
                $headers,
                [
                    'Content-Type' => 'application/json',
                ]
            ),
            false
        );
    }
}

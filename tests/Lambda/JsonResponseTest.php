<?php declare(strict_types=1);

namespace Test\Nuvola\AwsLambdaFramework\Lambda;

use Nuvola\AwsLambdaFramework\Lambda\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $response = new JsonResponse(
            [
                'foo' => 'bar',
            ],
            200,
            [
                'Content-Length' => 13,
            ]
        );

        $expected = <<<'JSON'
{
    "body": "{\"foo\":\"bar\"}",
    "statusCode": 200,
    "headers": {
        "Content-Length": 13,
        "Content-Type": "application/json"
    },
    "isBase64Encoded": false
}
JSON;
        self::assertJsonStringEqualsJsonString($expected, \json_encode($response));
    }
}

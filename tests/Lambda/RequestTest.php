<?php declare(strict_types=1);

namespace Test\Nuvola\AwsLambdaFramework\Lambda;

use Nuvola\AwsLambdaFramework\Lambda\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCreateFromApiGatewayRequest(): void
    {
        $json = <<<'JSON'
{
    "resource": "/{proxy+}",
    "path": "/test",
    "httpMethod": "POST",
    "headers": {
        "Accept": "application/json",
        "Accept-Encoding": "gzip, deflate",
        "cache-control": "no-cache",
        "CloudFront-Forwarded-Proto": "https",
        "CloudFront-Is-Desktop-Viewer": "true",
        "CloudFront-Is-Mobile-Viewer": "false",
        "CloudFront-Is-SmartTV-Viewer": "false",
        "CloudFront-Is-Tablet-Viewer": "false",
        "CloudFront-Viewer-Country": "PL",
        "Content-Type": "application/json",
        "Host": "foo.execute-api.eu-west-1.amazonaws.com",
        "Via": "1.1 foo.cloudfront.net (CloudFront)",
        "X-Amz-Cf-Id": "foo",
        "X-Amzn-Trace-Id": "Root=foo",
        "X-Forwarded-For": "127.0.0.1, 127.0.0.2",
        "X-Forwarded-Port": "443",
        "X-Forwarded-Proto": "https"
    },
    "multiValueHeaders": {
        "Accept": [
            "application/json"
        ],
        "Accept-Encoding": [
            "gzip, deflate"
        ],
        "cache-control": [
            "no-cache"
        ],
        "CloudFront-Forwarded-Proto": [
            "https"
        ],
        "CloudFront-Is-Desktop-Viewer": [
            "true"
        ],
        "CloudFront-Is-Mobile-Viewer": [
            "false"
        ],
        "CloudFront-Is-SmartTV-Viewer": [
            "false"
        ],
        "CloudFront-Is-Tablet-Viewer": [
            "false"
        ],
        "CloudFront-Viewer-Country": [
            "PL"
        ],
        "Content-Type": [
            "application/json"
        ],
        "Host": [
            "foo.execute-api.eu-west-1.amazonaws.com"
        ],
        "Via": [
            "1.1 foo.cloudfront.net (CloudFront)"
        ],
        "X-Amz-Cf-Id": [
            "foo"
        ],
        "X-Amzn-Trace-Id": [
            "Root=foo"
        ],
        "X-Forwarded-For": [
            "127.0.0.1, 127.0.0.2"
        ],
        "X-Forwarded-Port": [
            "443"
        ],
        "X-Forwarded-Proto": [
            "https"
        ]
    },
    "queryStringParameters": {
        "lorem": "ipsum"
    },
    "multiValueQueryStringParameters": {
        "lorem": [
            "ipsum"
        ]
    },
    "pathParameters": {
        "proxy": "test"
    },
    "stageVariables": null,
    "requestContext": {
        "resourceId": "mk7vva",
        "resourcePath": "/{proxy+}",
        "httpMethod": "POST",
        "extendedRequestId": "V0u6YHhTDoEFmew=",
        "requestTime": "28/Feb/2019:18:41:57 +0000",
        "path": "/default/test",
        "accountId": "12481632",
        "protocol": "HTTP/1.1",
        "stage": "default",
        "domainPrefix": "foo",
        "requestTimeEpoch": 12481632,
        "requestId": "foo",
        "identity": {
            "cognitoIdentityPoolId": null,
            "accountId": null,
            "cognitoIdentityId": null,
            "caller": null,
            "sourceIp": "127.0.0.1",
            "accessKey": null,
            "cognitoAuthenticationType": null,
            "cognitoAuthenticationProvider": null,
            "userArn": null,
            "user": null
        },
        "domainName": "foo.execute-api.eu-west-1.amazonaws.com",
        "apiId": "foo"
    },
    "body": "{\"foo\":\"bar\"}",
    "isBase64Encoded": false
}
JSON;

        $request = Request::create(\json_decode($json, true));

        self::assertInstanceOf(Request::class, $request);

        self::assertInstanceOf(\ArrayObject::class, $request->getContext());
        self::assertEquals('default', $request->getContext()->offsetGet('stage'));
        self::assertInstanceOf(\ArrayObject::class, $request->getHeaders());
        self::assertEquals('gzip, deflate', $request->getHeaders()->offsetGet('Accept-Encoding'));
        self::assertEquals('application/json', $request->getHeader('Accept'));
        self::assertEquals('POST', $request->getHttpMethod());
        self::assertEquals('/test', $request->getPath());
        self::assertInstanceOf(\ArrayObject::class, $request->getQueryStringParameters());
        self::assertEquals('ipsum', $request->getQueryStringParameters()->offsetGet('lorem'));
        self::assertEquals('{"foo":"bar"}', $request->getBody());
    }
}

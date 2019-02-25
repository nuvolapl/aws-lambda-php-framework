<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Lambda;

use Nuvola\AwsLambdaFramework\Exception\InvocationException;

class Request
{
    /**
     * @var \ArrayObject
     */
    private $context;

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \ArrayObject
     */
    private $queryStringParameters;

    /**
     * @var \ArrayObject
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var bool
     */
    private $isBase64Encoded;

    private function __construct(\ArrayObject $context, string $httpMethod, string $path, \ArrayObject $queryStringParameters, \ArrayObject $headers, string $body, bool $isBase64Encoded)
    {
        $this->context = $context;
        $this->httpMethod = $httpMethod;
        $this->path = $path;
        $this->queryStringParameters = $queryStringParameters;
        $this->headers = $headers;
        $this->body = $body;
        $this->isBase64Encoded = $isBase64Encoded;
    }

    public static function createFromInvocation(string $url, array &$headers): self
    {
        $curl = \curl_init();

        \curl_setopt($curl, \CURLOPT_URL, $url);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt(
            $curl,
            \CURLOPT_HEADERFUNCTION,
            function ($curl, string $tr) use (&$headers) {
                $td = \explode(': ', $tr, 2);

                if (isset($td[1])) {
                    $headers[$td[0]] = \trim($td[1]);
                }

                return \strlen($tr);
            }
        );

        if (false === ($response = \curl_exec($curl))) {
            throw new InvocationException(\curl_error($curl));
        }

        \curl_close($curl);

        return Request::createFromJson($response);
    }

    private static function createFromJson(string $json): self
    {
        $json = \json_decode($json, true);

        return new self(
            new \ArrayObject($json['requestContext']),
            $json['httpMethod'],
            $json['path'],
            new \ArrayObject($json['queryStringParameters']),
            new \ArrayObject($json['headers']),
            $json['body'],
            $json['isBase64Encoded']
        );
    }

    public function getContext(): \ArrayObject
    {
        return $this->context;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryStringParameters(): \ArrayObject
    {
        return $this->queryStringParameters;
    }

    public function getHeaders(): \ArrayObject
    {
        return $this->headers;
    }

    public function getHeader(string $key): string
    {
        if (false === $this->headers->offsetExists($key)) {
            throw new \InvalidArgumentException("Header \"{$key}\" not found.");
        }

        return $this->headers->offsetGet($key);
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isBase64Encoded(): bool
    {
        return $this->isBase64Encoded;
    }
}

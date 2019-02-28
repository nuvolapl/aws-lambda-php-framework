<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Lambda;

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
     * @var string|null
     */
    private $body;

    /**
     * @var bool
     */
    private $isBase64Encoded;

    private function __construct(\ArrayObject $context, string $httpMethod, string $path, \ArrayObject $queryStringParameters, \ArrayObject $headers, string $body = null, bool $isBase64Encoded = false)
    {
        $this->context = $context;
        $this->httpMethod = $httpMethod;
        $this->path = $path;
        $this->queryStringParameters = $queryStringParameters;
        $this->headers = $headers;
        $this->body = $body;
        $this->isBase64Encoded = $isBase64Encoded;
    }

    public static function create(array $data): self
    {
        return new self(
            new \ArrayObject($data['requestContext']),
            $data['httpMethod'],
            $data['path'],
            new \ArrayObject($data['queryStringParameters'] ?? []),
            new \ArrayObject($data['headers'] ?? []),
            $data['body'],
            $data['isBase64Encoded']
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function isBase64Encoded(): bool
    {
        return $this->isBase64Encoded;
    }
}

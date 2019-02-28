<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Lambda;

class Response implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $response = [];

    public function __construct(string $body = '', int $statusCode = 200, array $headers = [], bool $isBase64Encoded = false)
    {
        $this
            ->setBody($body)
            ->setStatusCode($statusCode)
            ->setHeaders($headers)
            ->setIsBase64Encoded($isBase64Encoded)
        ;
    }

    public function jsonSerialize(): array
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->response['statusCode'];
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->response['statusCode'] = $statusCode;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->response['headers'];
    }

    public function getHeader(string $name): string
    {
        if (false === $this->hasHeader($name)) {
            throw new \InvalidArgumentException("Header \"{$name}\" not found.");
        }

        return $this->response['headers'][$name];
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->response['headers'][$name]);
    }

    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }

        return $this;
    }

    public function addHeader(string $name, $value): self
    {
        $this->response['headers'][$name] = $value;

        return $this;
    }

    public function getBody(): string
    {
        return $this->response['body'];
    }

    public function setBody(string $body): self
    {
        $this->response['body'] = $body;

        return $this;
    }

    public function isBase64Encoded(): bool
    {
        return $this->response['isBase64Encoded'];
    }

    public function setIsBase64Encoded(bool $isBase64Encoded): self
    {
        $this->response['isBase64Encoded'] = $isBase64Encoded;

        return $this;
    }
}

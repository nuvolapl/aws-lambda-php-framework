<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Lambda;

use Nuvola\AwsLambdaFramework\Exception\InvocationException;

class Response
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

    public function send(string $invocation): void
    {
        $content = \json_encode($this->response);

        $curl = \curl_init();

        \curl_setopt($curl, \CURLOPT_URL, $invocation);
        \curl_setopt($curl, \CURLOPT_POST, true);
        \curl_setopt($curl, \CURLOPT_POSTFIELDS, $content);
        \curl_setopt(
            $curl,
            \CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . \strlen($content)
            ]
        );

        if (false === \curl_exec($curl)) {
            throw new InvocationException(\curl_error($curl));
        }

        \curl_close($curl);
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

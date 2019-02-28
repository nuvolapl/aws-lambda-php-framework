<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework\Http;

use Nuvola\AwsLambdaFramework\Exception\HttpException;

class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get(string $urn, array &$headers): array
    {
        $curl = \curl_init();

        \curl_setopt($curl, \CURLOPT_URL, $this->createUri($urn));
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
            throw new HttpException(\curl_error($curl));
        }

        \curl_close($curl);

        return \json_decode($response, true);
    }

    public function post(string $urn, string $content): void
    {
        $curl = \curl_init();

        \curl_setopt($curl, \CURLOPT_URL, $this->createUri($urn));
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
            throw new HttpException(\curl_error($curl));
        }

        \curl_close($curl);
    }

    private function createUri(string $urn): string
    {
        return "{$this->url}{$urn}";
    }
}

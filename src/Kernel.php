<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework;

use Nuvola\AwsLambdaFramework\Lambda\Request;
use Nuvola\AwsLambdaFramework\Lambda\Response;

class Kernel
{
    /**
     * @var string
     */
    private $handler;

    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    public function __construct(string $handler)
    {
        $this->handler = $handler;
    }

    public function registerHandler(HandlerInterface $handler): void
    {
        $this->handlers[\get_class($handler)] = $handler;
    }

    public function handle(Request $request): Response
    {
        if (false === isset($this->handlers[$this->handler])) {
            throw new \RuntimeException("Handler {$this->handler} is not registered.");
        }

        return $this->handlers[$this->handler]($request);
    }
}

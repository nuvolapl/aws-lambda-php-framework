<?php declare(strict_types=1);

namespace Nuvola\AwsLambdaFramework;

use Nuvola\AwsLambdaFramework\Lambda\Request;
use Nuvola\AwsLambdaFramework\Lambda\Response;

interface HandlerInterface
{
    public function __invoke(Request $request): Response;
}

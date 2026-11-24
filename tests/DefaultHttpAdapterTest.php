<?php

declare(strict_types=1);

namespace Http\Adapter\Guzzle8\Tests;

use Http\Adapter\Guzzle8\Client;
use Http\Client\Tests\HttpClientTest;
use Psr\Http\Client\ClientInterface;

/**
 * @author David Buchmann <mail@davidbu.ch>
 */
class DefaultHttpAdapterTest extends HttpClientTest
{
    protected function createHttpAdapter(): ClientInterface
    {
        return new Client();
    }
}

<?php

declare(strict_types=1);

namespace Http\Adapter\Guzzle8\Tests;

use GuzzleHttp\Handler\StreamHandler;

/**
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class StreamHttpAsyncAdapter extends AbstractHttpAsyncAdapter
{
    protected function createHandler()
    {
        return new StreamHandler();
    }
}

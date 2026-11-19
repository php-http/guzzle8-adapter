<?php

declare(strict_types=1);

namespace Http\Adapter\Guzzle8\Tests;

use GuzzleHttp\Handler\StreamHandler;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class StreamHttpAdapter extends AbstractHttpAdapter
{
    protected function createHandler()
    {
        return new StreamHandler();
    }
}

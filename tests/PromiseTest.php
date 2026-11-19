<?php

declare(strict_types=1);

namespace Http\Adapter\Guzzle8\Tests;

use GuzzleHttp\Promise\RejectedPromise;
use Http\Adapter\Guzzle8\Promise;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class PromiseTest extends TestCase
{
    use ProphecyTrait;

    public function testNonDomainExceptionIsHandled(): void
    {
        $this->expectException(\Exception::class);

        $request = $this->prophesize(RequestInterface::class);
        $promise = new RejectedPromise(new \Exception());

        $guzzlePromise = new Promise($promise, $request->reveal());

        $guzzlePromise->wait();
    }
}

<?php

declare(strict_types=1);

namespace Http\Adapter\Guzzle8\Tests;

use GuzzleHttp\Exception as GuzzleExceptions;
use Http\Adapter\Guzzle8\Exception\UnexpectedValueException;
use Http\Adapter\Guzzle8\Promise;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\NetworkException;
use Http\Client\Exception\RequestException;
use Http\Client\Exception\TransferException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author George Mponos <gmponos@gmail.com>
 */
final class PromiseExceptionTest extends TestCase
{
    /**
     * @dataProvider exceptionThatIsThrownForGuzzleExceptionProvider
     */
    #[DataProvider('exceptionThatIsThrownForGuzzleExceptionProvider')]
    public function testExceptionThatIsThrownForGuzzleException(
        RequestInterface $request,
        $reason,
        string $adapterExceptionClass
    ): void {
        $guzzlePromise = new \GuzzleHttp\Promise\Promise();
        $guzzlePromise->reject($reason);
        $promise = new Promise($guzzlePromise, $request);

        try {
            $promise->wait();
        } catch (\Throwable $e) {
            $this->assertSame($adapterExceptionClass, get_class($e));

            if ($reason instanceof \Throwable) {
                $this->assertSame($reason, $e->getPrevious());
            }
            if ($reason instanceof GuzzleExceptions\ResponseException) {
                $this->assertInstanceOf(HttpException::class, $e);
                $this->assertSame($reason->getResponse(), $e->getResponse());
            }

            return;
        }

        $this->fail('Expected exception '.$adapterExceptionClass.' was not thrown');
    }

    public static function exceptionThatIsThrownForGuzzleExceptionProvider(): array
    {
        $request = (new PromiseExceptionTest('request'))->getMockBuilder(RequestInterface::class)->getMock();
        $response = (new PromiseExceptionTest('response'))->getMockBuilder(ResponseInterface::class)->getMock();

        return [
            // no response received
            [$request, new GuzzleExceptions\NetworkException('foo', $request), NetworkException::class],
            [$request, new GuzzleExceptions\NetworkTimeoutException('foo', $request), NetworkException::class],
            [$request, new GuzzleExceptions\ConnectException('foo', $request), NetworkException::class],
            [$request, new GuzzleExceptions\ConnectTimeoutException('foo', $request), NetworkException::class],
            [$request, new GuzzleExceptions\RequestException('foo', $request), RequestException::class],
            // response received
            [$request, new GuzzleExceptions\ResponseException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\TooManyRedirectsException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\BadResponseException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\ClientException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\ServerException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\ResponseTransferException('foo', $request, $response), HttpException::class],
            [$request, new GuzzleExceptions\ResponseTimeoutException('foo', $request, $response), HttpException::class],
            // other Guzzle exceptions
            [$request, new GuzzleExceptions\TransferException('foo', $request), TransferException::class],
            [$request, new GuzzleExceptions\HandlerClosedException('foo', $request), TransferException::class],
            [$request, new GuzzleExceptions\InvalidArgumentException('foo'), TransferException::class],
            // Non PSR-18 Exceptions thrown
            [$request, new \Exception('foo'), TransferException::class],
            [$request, new \Error('foo'), TransferException::class],
            [$request, 'whatever', UnexpectedValueException::class],
        ];
    }
}

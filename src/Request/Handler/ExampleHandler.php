<?php

/*
 * This file is part of the ReactPHP Foundation <https://github.com/itnelo/reactphp-foundation>.
 *
 * (c) 2020 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/mit MIT
 */

declare(strict_types=1);

namespace Foundation\Request\Handler;

use Exception;
use Foundation\Request\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\Response;

/**
 * Request handler example
 */
class ExampleHandler implements HandlerInterface
{
    /**
     * Logs handler events
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Handler's unique identifier
     *
     * @var string
     */
    private static string $uid;

    /**
     * ExampleHandler constructor.
     *
     * @param LoggerInterface $logger Logs handler events
     *
     * @throws Exception
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        self::$uid = bin2hex(random_bytes(4));
    }

    /**
     * {@inheritDoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        $this->logger->debug('An HTTP request has been received.');

        $responseBody = json_encode(
            [
                'uid' => self::$uid,
                'ts'  => time(),
            ]
        );

        $response = new Response(
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            $responseBody
        );

        return $response;
    }

    /**
     * Performs proxy pass to the handle method with request processing logic
     *
     * @param RequestInterface $request PSR-7 request message
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request)
    {
        return $this->handle($request);
    }
}

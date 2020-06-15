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
     * ExampleHandler constructor.
     *
     * @param LoggerInterface $logger Logs handler events
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        $response = new Response();

        $this->logger->debug('An HTTP request has been received.');

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

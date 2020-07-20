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

namespace Foundation\Request;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

/**
 * Accepts PSR-7 request and generates an appropriate response
 *
 * Implementation should be provided by the business domain level
 *
 * @see https://www.php-fig.org/psr/psr-7
 */
interface HandlerInterface
{
    /**
     * Returns PSR-7 response message
     *
     * @param RequestInterface $request PSR-7 request message
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request): ResponseInterface;
}

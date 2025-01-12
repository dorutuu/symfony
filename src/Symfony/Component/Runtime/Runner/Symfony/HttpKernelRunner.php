<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Runtime\Runner\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Runtime\RunnerInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class HttpKernelRunner implements RunnerInterface
{
    public function __construct(
        private readonly HttpKernelInterface $kernel,
        private readonly Request $request,
        private readonly bool $debug = false,
    ) {
    }

    public function run(): int
    {
        $response = $this->kernel->handle($this->request);
        $response->send(!$this->debug);

        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($this->request, $response);
        }

        return 0;
    }
}

<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader;


use Guennichi\PropertyLoader\Context\ExecutionContextInterface;

interface LoaderHandlerInterface
{
    /**
     * Handle a given loader.
     *
     * @param Loader $loader
     * @param ExecutionContextInterface $context
     */
    public function handle(Loader $loader, ExecutionContextInterface $context): void;
}

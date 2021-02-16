<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Loaders;


use Guennichi\PropertyLoader\Context\ExecutionContextInterface;
use Guennichi\PropertyLoader\Loader;
use Guennichi\PropertyLoader\LoaderHandler;
use ReflectionNamedType;

class AwareHandler extends LoaderHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Loader $loader, ExecutionContextInterface $context): void
    {
        $object = $context->getObject();
        $targetMetadata = $context->getTargetPropertyMetadata();

        if (!$targetMetadata->isInitialized($object)) {
            // Ignore
            return;
        }

        $targetType = $targetMetadata->getReflectionProperty()->getType();
        if (!$targetType instanceof ReflectionNamedType) {
            return;
        }

        if ($targetType->isBuiltin() || false === class_exists($targetType->getName())) {
            return;
        }

        $context->getPropertyLoader()
            ->load($targetMetadata->getPropertyValue($object));
    }
}

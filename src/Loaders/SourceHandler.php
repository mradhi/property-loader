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

abstract class SourceHandler extends LoaderHandler
{
    /**
     * Get target value that'll be stored later in the target property for the object.
     *
     * @param $sourceValue
     * @param Source $loader
     * @param ExecutionContextInterface $context
     *
     * @return mixed
     */
    public abstract function getTargetValue($sourceValue, Source $loader, ExecutionContextInterface $context);

    /**
     * @inheritDoc
     *
     * @param Source $loader
     */
    public function handle(Loader $loader, ExecutionContextInterface $context): void
    {
        $sourceProperty = $context->getClassMetadata()->getReflectionClass()->getProperty($loader->source);
        $object = $context->getObject();

        $value = $this->getTargetValue(
            $sourceProperty->isInitialized($object) ? $sourceProperty->getValue($object) : null,
            $loader,
            $context
        );

        $context->getTargetPropertyMetadata()->setPropertyValue($value, $object);
    }
}

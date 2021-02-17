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


use Closure;
use Guennichi\PropertyLoader\Context\ExecutionContext;
use Guennichi\PropertyLoader\Context\ExecutionContextInterface;
use Guennichi\PropertyLoader\Mapping\Factory\MetadataFactory;
use Guennichi\PropertyLoader\Mapping\Loader\LoaderInterface;
use Guennichi\PropertyLoader\Mapping\Loader\StaticMethodLoader;

class PropertyLoader
{
    /**
     * @var MetadataFactory
     */
    private MetadataFactory $metadataFactory;

    /**
     * @var ExecutionContextInterface
     */
    private ExecutionContextInterface $context;

    /**
     * @param LoaderInterface|null $mappingLoader
     */
    public function __construct(LoaderInterface $mappingLoader = null)
    {
        // Initialize the context for this property loader
        $this->context = new ExecutionContext($this);
        $this->metadataFactory = new MetadataFactory($mappingLoader ?? new StaticMethodLoader());
    }

    /**
     * Load properties for a given object.
     *
     * @param object $object
     * @param Closure $handler
     */
    public function load(object $object, Closure $handler): void
    {
        $classMetadata = $this->metadataFactory->getMetadataFor($object);

        $this->context
            ->setObject($object)
            ->setClassMetadata($classMetadata);

        foreach ($classMetadata->getProperties() as $targetProperty) {
            $loader = $targetProperty->getLoader();

            $this->context
                ->setPropertyMetadata($targetProperty)
                ->setLoader($loader);

            $handler($loader, $this->context);
        }
    }

    /**
     * Get the current context for this instance.
     *
     * @return ExecutionContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }
}

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
     * @var LoaderHandlerInterface[]
     */
    private array $handlers;

    /**
     * @var ExecutionContextInterface
     */
    private ExecutionContextInterface $context;

    /**
     * @param LoaderInterface|null $mappingLoader
     * @param array $handlers
     */
    public function __construct(LoaderInterface $mappingLoader = null, array $handlers = [])
    {
        $this->handlers = $handlers;
        // Initialize the context for this property loader
        $this->context = new ExecutionContext($this);
        $this->metadataFactory = new MetadataFactory($mappingLoader ?? new StaticMethodLoader());
    }

    /**
     * Load properties for a given object.
     *
     * @param object $object
     */
    public function load(object $object): void
    {
        $classMetadata = $this->metadataFactory->getMetadataFor($object);

        $this->context
            ->setObject($object)
            ->setClassMetadata($classMetadata);

        foreach ($classMetadata->getTargetProperties() as $targetProperty) {
            $loader = $targetProperty->getLoader();

            $this->context
                ->setTargetPropertyMetadata($targetProperty)
                ->setLoader($loader);

            foreach ($this->handlers as $handler) {
                if (get_class($handler) === $loader->handledBy()) {
                    $handler->handle($loader, $this->context);
                }
            }
        }
    }
}

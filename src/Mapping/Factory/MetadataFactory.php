<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Mapping\Factory;


use Guennichi\PropertyLoader\Exception\NoSuchMetadataException;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Mapping\Loader\LoaderInterface;
use function get_class;
use function is_object;

class MetadataFactory
{
    /**
     * @var LoaderInterface|null
     */
    private ?LoaderInterface $loader;

    /**
     * The loaded metadata, indexed by class name.
     *
     * @var ClassMetadata[]
     */
    private array $loadedClasses = [];

    /**
     * @param LoaderInterface|null $loader
     */
    public function __construct(LoaderInterface $loader = null)
    {
        $this->loader = $loader;
    }

    /**
     * If the method was called with the same class name (or an object of that
     * class) before, the same metadata instance is returned.
     *
     * @param string|object $value
     *
     * @return ClassMetadata
     */
    public function getMetadataFor($value): ClassMetadata
    {
        if (!is_object($value) && !is_string($value)) {
            throw new NoSuchMetadataException(sprintf('Cannot create metadata for non-objects. Got: "%s"', get_debug_type($value)));
        }

        $className = ltrim(is_object($value) ? get_class($value) : $value, '\\');

        if (isset($this->loadedClasses[$className])) {
            return $this->loadedClasses[$className];
        }

        if (!class_exists($className)) {
            throw new NoSuchMetadataException(sprintf('The class or interface "%s" does not exist.', $className));
        }

        $metadata = new ClassMetadata($className);

        if (null !== $this->loader) {
            $this->loader->loadClassMetadata($metadata);
        }

        // Include loaders from the parent class
        $this->mergeLoaders($metadata);

        return $this->loadedClasses[$className] = $metadata;
    }

    private function mergeLoaders(ClassMetadata $metadata): void
    {
        if ($metadata->getReflectionClass()->isInterface()) {
            return;
        }

        // Include loaders from the parent class
        if ($parent = $metadata->getReflectionClass()->getParentClass()) {
            $metadata->mergePropertyLoaders($this->getMetadataFor($parent->name));
        }
    }
}

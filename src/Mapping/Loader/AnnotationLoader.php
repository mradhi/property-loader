<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Mapping\Loader;


use Doctrine\Common\Annotations\Reader;
use Guennichi\PropertyLoader\Loader;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use ReflectionProperty;

class AnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader|null
     */
    protected ?Reader $reader;

    /**
     * @param Reader|null $reader
     */
    public function __construct(Reader $reader = null)
    {
        $this->reader = $reader;
    }

    /**
     * @inheritDoc
     */
    public function loadClassMetadata(ClassMetadata $metadata): bool
    {
        if (null === $this->reader) {
            return false;
        }

        $reflClass = $metadata->getReflectionClass();
        $className = $reflClass->name;
        $success = false;

        foreach ($reflClass->getProperties() as $property) {
            if ($property->getDeclaringClass()->name === $className) {
                foreach ($this->getAnnotations($property) as $loader) {
                    if ($loader instanceof Loader) {
                        $metadata->addPropertyLoader($property->name, $loader);
                    }

                    $success = true;
                }
            }
        }

        return $success;
    }

    /**
     * Get all annotations for a given reflection object.
     *
     * @param object $reflection
     *
     * @return iterable
     */
    private function getAnnotations(object $reflection): iterable
    {
        if ($reflection instanceof ReflectionProperty) {
            yield from $this->reader->getPropertyAnnotations($reflection);
        }
    }
}

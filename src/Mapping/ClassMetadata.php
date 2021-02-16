<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Mapping;


use Guennichi\PropertyLoader\Exception\MappingException;
use Guennichi\PropertyLoader\Loader;
use ReflectionClass;
use ReflectionException;

class ClassMetadata
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var PropertyMetadata[]
     */
    protected array $properties = [];

    /**
     * @var ReflectionClass|null
     */
    protected ?ReflectionClass $reflClass = null;

    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->name = $class;
    }

    /**
     * Merges the loaders of the given metadata into this object.
     *
     * @param ClassMetadata $source
     */
    public function mergeLoaders(self $source): void
    {
        foreach ($source->getProperties() as $member) {
            $property = $member->getPropertyName();

            if (!isset($this->properties[$property])) {
                $this->properties[$property] = clone $member;
            }
        }
    }

    /**
     * Add new property with a given loader.
     *
     * @param string $target
     * @param Loader $loader
     */
    public function addPropertyLoader(string $target, Loader $loader): void
    {
        if (isset($this->properties[$target])) {
            throw new MappingException(sprintf('Loader for "%s" in "%s" is already mapped.', $target, $this->getClassName()));
        }

        $this->addProperty(new PropertyMetadata($this->getClassName(), $target, $loader));
    }

    /**
     * Add property metadata object.
     *
     * @param PropertyMetadata $member
     */
    public function addProperty(PropertyMetadata $member): void
    {
        $property = $member->getPropertyName();

        $this->properties[$property] = $member;
    }

    /**
     * Get target properties.
     *
     * @return PropertyMetadata[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Get the reflection class object.
     *
     * @return ReflectionClass
     *
     * @throws ReflectionException
     */
    public function getReflectionClass(): ReflectionClass
    {
        if (null === $this->reflClass) {
            $this->reflClass = new ReflectionClass($this->getClassName());
        }

        return $this->reflClass;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->name;
    }
}

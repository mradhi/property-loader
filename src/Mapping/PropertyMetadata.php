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


use Guennichi\PropertyLoader\Exception\PropertyLoaderException;
use Guennichi\PropertyLoader\Loader;
use ReflectionException;
use ReflectionProperty;

class PropertyMetadata
{
    /**
     * @var Loader
     */
    private Loader $loader;

    /**
     * @var string
     */
    private string $class;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var ReflectionProperty|null
     */
    private ?ReflectionProperty $reflProperty = null;

    /**
     * @param string $class
     * @param string $name
     * @param Loader $loader
     */
    public function __construct(string $class, string $name, Loader $loader)
    {
        $this->class = $class;
        $this->name = $name;
        $this->loader = $loader;
    }

    /**
     * Get the property value from an object.
     *
     * @param $object
     *
     * @return mixed
     */
    public function getPropertyValue($object)
    {
        return $this->getReflectionProperty()->getValue($object);
    }

    /**
     * Check if the property is initialized inside a given object.
     *
     * @param $object
     *
     * @return bool
     */
    public function isInitialized($object): bool
    {
        return $this->getReflectionProperty()->isInitialized($object);
    }

    /**
     * Set value for property inside a given object.
     *
     * @param $value
     *
     * @param $object
     */
    public function setPropertyValue($value, $object)
    {
        $this->getReflectionProperty()->setValue($object, $value);
    }

    /**
     * Get property loader.
     *
     * @return Loader
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }

    /**
     * Get the reflection property object.
     *
     * @return ReflectionProperty
     *
     * @throws ReflectionException
     */
    public function getReflectionProperty(): ReflectionProperty
    {
        if (null === $this->reflProperty) {
            $originalClass = $className = $this->getClassName();

            while (!property_exists($this->getClassName(), $this->getPropertyName())) {
                $className = get_parent_class($className);

                if (false === $className) {
                    throw new PropertyLoaderException(sprintf('Property "%s" does not exist in class "%s".', $this->getPropertyName(), $originalClass));
                }
            }

            $reflection = new ReflectionProperty($className, $this->getPropertyName());
            $reflection->setAccessible(true);

            $this->reflProperty = $reflection;
        }

        return $this->reflProperty;
    }

    /**
     * Get property inner classname.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->class;
    }

    /**
     * Get property name.
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->name;
    }
}

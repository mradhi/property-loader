<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Context;


use Guennichi\PropertyLoader\Loader;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Mapping\PropertyMetadata;
use Guennichi\PropertyLoader\PropertyLoader;

interface ExecutionContextInterface
{
    /**
     * Returns the currently loaded object.
     *
     * @return object
     */
    public function getObject(): object;

    /**
     * Sets the currently loaded object.
     *
     * @param object $object
     *
     * @return self
     * @internal Used by the property loader engine. Should not be called by user
     *           code.
     *
     */
    public function setObject(object $object): self;

    /**
     * Sets the currently loaded loader.
     *
     * @param Loader $loader
     *
     * @return self
     * @internal Used by the property loader engine. Should not be called by user
     *           code.
     *
     */
    public function setLoader(Loader $loader): self;

    /**
     * Get currently targeted property metadata.
     *
     * @return PropertyMetadata
     */
    public function getTargetPropertyMetadata(): PropertyMetadata;

    /**
     * Get current class metadata
     *
     * @return ClassMetadata
     */
    public function getClassMetadata(): ClassMetadata;

    /**
     * Sets the currently loaded class metadata.
     *
     * @param ClassMetadata $classMetadata
     *
     * @return $this
     * @internal Used by the property loader engine. Should not be called by user
     *           code.
     *
     */
    public function setClassMetadata(ClassMetadata $classMetadata): self;

    /**
     * Get the property loader instance.
     *
     * @return PropertyLoader
     */
    public function getPropertyLoader(): PropertyLoader;

    /**
     * Sets the currently loaded property metadata.
     *
     * @param PropertyMetadata $targetPropertyMetadata
     *
     * @return $this
     * @internal Used by the property loader engine. Should not be called by user
     *           code.
     *
     */
    public function setTargetPropertyMetadata(PropertyMetadata $targetPropertyMetadata): self;
}

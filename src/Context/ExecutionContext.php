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

class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var PropertyLoader
     */
    private PropertyLoader $propertyLoader;

    /**
     * @var ClassMetadata
     */
    private ClassMetadata $classMetadata;

    /**
     * @var PropertyMetadata
     */
    private PropertyMetadata $targetPropertyMetadata;

    /**
     * @var Loader
     */
    private Loader $loader;

    /**
     * @var object
     */
    private object $object;

    /**
     * @param PropertyLoader $propertyLoader
     */
    public function __construct(PropertyLoader $propertyLoader)
    {
        $this->propertyLoader = $propertyLoader;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyLoader(): PropertyLoader
    {
        return $this->propertyLoader;
    }

    /**
     * @inheritDoc
     */
    public function getClassMetadata(): ClassMetadata
    {
        return $this->classMetadata;
    }

    /**
     * @inheritDoc
     */
    public function setClassMetadata(ClassMetadata $classMetadata): ExecutionContext
    {
        $this->classMetadata = $classMetadata;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTargetPropertyMetadata(): PropertyMetadata
    {
        return $this->targetPropertyMetadata;
    }

    /**
     * @inheritDoc
     */
    public function setTargetPropertyMetadata(PropertyMetadata $targetPropertyMetadata): ExecutionContext
    {
        $this->targetPropertyMetadata = $targetPropertyMetadata;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLoader(Loader $loader): ExecutionContext
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * @inheritDoc
     */
    public function setObject(object $object): ExecutionContext
    {
        $this->object = $object;

        return $this;
    }
}

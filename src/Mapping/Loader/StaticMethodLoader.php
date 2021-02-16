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


use Guennichi\PropertyLoader\Exception\MappingException;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;

class StaticMethodLoader implements LoaderInterface
{
    /**
     * @var string
     */
    protected string $methodName;

    /**
     * @param string $methodName
     */
    public function __construct(string $methodName = 'configurePropertyLoaderMetadata')
    {
        $this->methodName = $methodName;
    }

    /**
     * @inheritDoc
     */
    public function loadClassMetadata(ClassMetadata $metadata): bool
    {
        $reflClass = $metadata->getReflectionClass();

        if (!$reflClass->isInterface() && $reflClass->hasMethod($this->methodName)) {
            $reflMethod = $reflClass->getMethod($this->methodName);

            if ($reflMethod->isAbstract()) {
                return false;
            }

            if (!$reflMethod->isStatic()) {
                throw new MappingException(sprintf('The method "%s::%s()" should be static.', $reflClass->name, $this->methodName));
            }

            if ($reflMethod->getDeclaringClass()->name !== $reflClass->name) {
                return false;
            }

            $reflMethod->invoke(null, $metadata);

            return true;
        }

        return false;
    }
}

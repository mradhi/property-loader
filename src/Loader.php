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


abstract class Loader
{
    /**
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        foreach ($args as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Classname handled by loader.
     *
     * @return string
     */
    public function handledBy(): string
    {
        return static::class . 'Handler';
    }
}

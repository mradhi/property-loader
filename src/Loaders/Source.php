<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Loaders;


use Guennichi\PropertyLoader\Loader;

abstract class Source extends Loader
{
    /**
     * @var string
     */
    public string $source;
}

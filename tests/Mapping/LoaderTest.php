<?php
/*
 * This file is part of the Property Loader package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PropertyLoader\Tests\Mapping;


use Guennichi\PropertyLoader\Loader;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    public function testConvertsArgsArrayToProperties(): void
    {
        $dummy = new DummyLoader(['foo' => 'bar']);

        $this->assertSame('bar', $dummy->foo);
    }

    public function testDefaultLoaderHandlerClass(): void
    {
        $dummy = new DummyLoader();
        $this->assertSame('Guennichi\PropertyLoader\Tests\Mapping\DummyLoaderHandler', $dummy->handledBy());
    }
}

class DummyLoader extends Loader
{

}

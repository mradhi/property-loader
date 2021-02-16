<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures;


use Guennichi\PropertyLoader\Tests\Fixtures\Loaders as Load;

class Child extends Root
{
    /**
     * @var string
     *
     * @Load\Gmail()
     */
    public string $foo;
}

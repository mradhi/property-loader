<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures;

use Guennichi\PropertyLoader\Tests\Fixtures\Loaders as Load;

class Root
{
    public string $name;

    /**
     * @var string
     *
     * @Load\Gmail()
     */
    public string $target;
}

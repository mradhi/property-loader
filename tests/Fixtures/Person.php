<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures;

use Guennichi\PropertyLoader\Tests\Fixtures\Loaders as Load;

class Person
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     *
     * @Load\Gmail(source="name")
     */
    public string $email;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

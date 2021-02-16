<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures;

use Guennichi\PropertyLoader\Loaders\Aware;
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

    /**
     * @var Person
     *
     * @Aware()
     */
    public Person $relatedPerson;

    public function __construct(string $name, ?string $relatedPersonName = null)
    {
        $this->name = $name;
        if (null !== $relatedPersonName) {
            $this->relatedPerson = new Person($relatedPersonName);
        }
    }
}

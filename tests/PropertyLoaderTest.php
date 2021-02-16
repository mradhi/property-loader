<?php


namespace Guennichi\PropertyLoader\Tests;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Loaders\AwareHandler;
use Guennichi\PropertyLoader\Loaders as Load;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
use Guennichi\PropertyLoader\PropertyLoader;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\GmailHandler;
use Guennichi\PropertyLoader\Tests\Fixtures\Person;
use PHPUnit\Framework\TestCase;

class PropertyLoaderTest extends TestCase
{
    public function testLoadProperties(): void
    {
        $object = new Person('person1', 'person2');

        $handlers = [
            new GmailHandler(),
            new AwareHandler()
        ];

        $propertyLoader = new PropertyLoader(new AnnotationLoader(new AnnotationReader()), $handlers);
        $propertyLoader->load($object);

        $this->assertSame('person1@gmail.com', $object->email);
        $this->assertSame('person2@gmail.com', $object->relatedPerson->email);
    }
}

class Bar
{
    public string $person;

    /**
     * @var string
     *
     * @Load\Gmail(source="person")
     */
    public string $personEmail;

    public function __construct(string $person)
    {
        $this->person = $person;
    }

    /*public static function configurePropertyLoaderMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyLoader('personEmail', new Load\Gmail(['source' => 'person']));
    }*/
}

class Foo
{
    public string $name;

    /**
     * @var string
     *
     * @Load\Gmail(source="name")
     */
    public string $email;

    /**
     * @var Bar
     *
     * @Load\Aware()
     */
    public Bar $person;

    /**
     * @param string $name
     * @param Bar $person
     */
    public function __construct(string $name, Bar $person)
    {
        $this->name = $name;
        $this->person = $person;
    }


    /*public static function configurePropertyLoaderMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyLoader('email', new Load\Gmail(['source' => 'name']));
        $metadata->addPropertyLoader('person', new Load\Aware());
    }*/
}

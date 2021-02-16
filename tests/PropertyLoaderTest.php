<?php


namespace Guennichi\PropertyLoader\Tests;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Context\ExecutionContextInterface;
use Guennichi\PropertyLoader\Loader;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders as Load;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
use Guennichi\PropertyLoader\PropertyLoader;
use PHPUnit\Framework\TestCase;

class PropertyLoaderTest extends TestCase
{
    public function testLoadProperties(): void
    {
        $object = new Foo('person1');

        $propertyLoader = new PropertyLoader(new AnnotationLoader(new AnnotationReader()));

        $propertyLoader->load($object, function (Loader $loader, ExecutionContextInterface $context) use ($object, $propertyLoader) {
            if (!$loader instanceof Load\Gmail) {
                return;
            }

            $this->assertSame($object, $context->getObject());
            $this->assertSame('email', $context->getPropertyMetadata()->getPropertyName());
            $this->assertSame('Guennichi\PropertyLoader\Tests\Foo', $context->getClassMetadata()->getClassName());

            $sourceProperty = $context->getClassMetadata()->getReflectionClass()->getProperty($loader->source);
            $object = $context->getObject();

            $value = $sourceProperty->getValue($object) . '@gmail.com';

            $context->getPropertyMetadata()->setPropertyValue($value, $object);
        });

        $this->assertSame('person1@gmail.com', $object->email);
    }
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
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }


    /*public static function configurePropertyLoaderMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyLoader('email', new Load\Gmail(['source' => 'name']));
        $metadata->addPropertyLoader('person', new Load\Aware());
    }*/
}

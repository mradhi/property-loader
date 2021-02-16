<?php


namespace Guennichi\PropertyLoader\Tests\Mapping\Factory;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Exception\NoSuchMetadataException;
use Guennichi\PropertyLoader\Mapping\Factory\MetadataFactory;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
use Guennichi\PropertyLoader\Mapping\Loader\LoaderInterface;
use Guennichi\PropertyLoader\Tests\Fixtures\Child;
use PHPUnit\Framework\TestCase;

class MetadataFactoryTest extends TestCase
{
    public function testCreateMetadataWithParentClasses(): void
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $factory = new MetadataFactory($loader);
        $metadata = $factory->getMetadataFor(Child::class);

        $this->assertCount(2, $metadata->getTargetProperties());
    }

    public function testGetSameMetadataObjectIfCalledMoreThanOne(): void
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $factory = new MetadataFactory($loader);

        $meta1 = $factory->getMetadataFor(Foo::class);
        $meta2 = $factory->getMetadataFor(Foo::class);

        $this->assertTrue($meta1 === $meta2);
    }

    public function testThrowExceptionIfNotValidClassnameOrObject(): void
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $factory = new MetadataFactory($loader);

        $exceptions = [];

        try {
            $factory->getMetadataFor(15);
        } catch (NoSuchMetadataException $e) {
            $exceptions[] = $e;
        }

        try {
            $factory->getMetadataFor('foo');
        } catch (NoSuchMetadataException $e) {
            $exceptions[] = $e;
        }

        $this->assertCount(2, $exceptions);

        $this->assertSame('Cannot create metadata for non-objects. Got: "int"', $exceptions[0]->getMessage());
        $this->assertSame('The class "foo" does not exist.', $exceptions[1]->getMessage());
    }
}

class Foo
{

}

interface FooInterface
{

}

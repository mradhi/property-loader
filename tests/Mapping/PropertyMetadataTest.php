<?php


namespace Guennichi\PropertyLoader\Tests\Mapping;


use Guennichi\PropertyLoader\Exception\PropertyLoaderException;
use Guennichi\PropertyLoader\Mapping\PropertyMetadata;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\Gmail;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class PropertyMetadataTest extends TestCase
{
    public function testCreatePropertyReflectionInstance(): void
    {
        $metadata = new PropertyMetadata(Foo::class, 'bar', new Gmail());
        $this->assertInstanceOf(ReflectionProperty::class, $metadata->getReflectionProperty());

        // It creates property reflection instance for a property existed on parent class
        $metadata = new PropertyMetadata(Foo::class, 'parentBar', new Gmail());
        $this->assertInstanceOf(ReflectionProperty::class, $metadata->getReflectionProperty());

        // It throws an exception if the property does not exist
        $this->expectException(PropertyLoaderException::class);
        $this->expectExceptionMessage('Property "invalid" does not exist in class "Guennichi\PropertyLoader\Tests\Mapping\Foo".');

        $metadata = new PropertyMetadata(Foo::class, 'invalid', new Gmail());
        $metadata->getReflectionProperty();
    }

    public function testGetPropertyValueFromObject(): void
    {
        $metadata = new PropertyMetadata(Foo::class, 'bar', new Gmail());
        $this->assertSame('dump data', $metadata->getPropertyValue(new Foo()));

        $metadata = new PropertyMetadata(Foo::class, 'parentBar', new Gmail());
        $this->assertSame('parent dump data', $metadata->getPropertyValue(new Foo()));
    }

    public function testSetPropertyValueFromObject(): void
    {
        // It set the value for a given property
        $metadata = new PropertyMetadata(Foo::class, 'bar', new Gmail());
        $metadata->setPropertyValue('new value', $foo = new Foo());

        $this->assertSame('new value', $foo->getBar());

        // It set the value for a given parent property
        $metadata = new PropertyMetadata(Foo::class, 'parentBar', new Gmail());
        $metadata->setPropertyValue('new parent value', $foo = new Foo());

        $this->assertSame('new parent value', $foo->getParentBar());
    }
}

class ParentFoo
{
    protected string $parentBar = 'parent dump data';

    /**
     * @return string
     */
    public function getParentBar(): string
    {
        return $this->parentBar;
    }
}

class Foo extends ParentFoo
{
    protected string $bar = 'dump data';

    /**
     * @return string
     */
    public function getBar(): string
    {
        return $this->bar;
    }
}

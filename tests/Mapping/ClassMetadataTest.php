<?php


namespace Guennichi\PropertyLoader\Tests\Mapping;


use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\Gmail;
use Guennichi\PropertyLoader\Tests\Fixtures\Person;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ClassMetadataTest extends TestCase
{
    public function testCreatesClassMetadataInstance(): void
    {
        $classMetadata = new ClassMetadata(Person::class);

        $this->assertInstanceOf(ClassMetadata::class, $classMetadata);
    }

    public function testGetReflectionClass(): void
    {
        $classMetadata = new ClassMetadata(Person::class);

        $this->assertInstanceOf(ReflectionClass::class, $refl = $classMetadata->getReflectionClass());
        $this->assertSame(Person::class, $refl->name);
    }

    public function testAddPropertyLoader(): void
    {
        $classMetadata = new ClassMetadata(Person::class);
        $classMetadata->addPropertyLoader('email', new Gmail(['source' => 'name']));

        $this->assertCount(1, $classMetadata->getTargetProperties());
    }
}

<?php


namespace Guennichi\PropertyLoader\Tests\Mapping\Loader;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Loaders\Aware;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
use Guennichi\PropertyLoader\Tests\Fixtures\Child;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\Gmail;
use Guennichi\PropertyLoader\Tests\Fixtures\Person;
use Guennichi\PropertyLoader\Tests\Fixtures\Root;
use PHPUnit\Framework\TestCase;

class AnnotationLoaderTest extends TestCase
{
    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $reader = new AnnotationReader();
        $loader = new AnnotationLoader($reader);
        $metadata = new ClassMetadata(Person::class);

        $this->assertTrue($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadataReturnsFalseIfNotSuccessful()
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $metadata = new ClassMetadata('\stdClass');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadata(): void
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $metadata = new ClassMetadata(Person::class);
        $loader->loadClassMetadata($metadata);

        $expected = new ClassMetadata(Person::class);
        $expected->addPropertyLoader('email', new Gmail(['source' => 'name']));

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        $this->assertEquals($expected, $metadata);
    }

    public function testLoadParentClassMetadata()
    {
        $loader = new AnnotationLoader(new AnnotationReader());

        // Load Parent MetaData
        $parent_metadata = new ClassMetadata(Root::class);
        $loader->loadClassMetadata($parent_metadata);

        $metadata = new ClassMetadata(Child::class);

        // Merge parent metaData
        $metadata->mergePropertyLoaders($parent_metadata);

        $loader->loadClassMetadata($metadata);

        $expected_parent = new ClassMetadata(Root::class);
        $expected_parent->addPropertyLoader('target', new Gmail());

        // load reflection class so that the comparison passes
        $expected_parent->getReflectionClass();

        $expected = new ClassMetadata(Child::class);
        $expected->addPropertyLoader('foo', new Gmail());
        $expected->mergePropertyLoaders($expected_parent);

        $expected->getReflectionClass();

        $this->assertEquals($expected, $metadata);
    }
}

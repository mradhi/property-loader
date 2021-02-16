<?php


namespace Guennichi\PropertyLoader\Tests\Mapping\Loader;


use Guennichi\PropertyLoader\Exception\MappingException;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Mapping\Loader\StaticMethodLoader;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\Gmail;
use PHPUnit\Framework\TestCase;

class StaticMethodLoaderTest extends TestCase
{
    public function testLoadClassMetadataReturnsTrueIfSuccessful(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderEntity::class);

        $this->assertTrue($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadataReturnsFalseIfNotSuccessful(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata('\stdClass');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadata(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderEntity::class);

        $loader->loadClassMetadata($metadata);

        $this->assertEquals(StaticLoaderEntity::$invokedWith, $metadata);
    }


    public function testLoadClassMetadataDoesNotRepeatLoadWithParentClasses(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderDocument::class);
        $loader->loadClassMetadata($metadata);
        $this->assertCount(0, $metadata->getTargetProperties());

        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(BaseStaticLoaderDocument::class);
        $loader->loadClassMetadata($metadata);
        $this->assertCount(1, $metadata->getTargetProperties());
    }

    public function testLoadClassMetadataIgnoresInterfaces(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderInterface::class);

        $loader->loadClassMetadata($metadata);

        $this->assertCount(0, $metadata->getTargetProperties());
    }

    public function testLoadClassMetadataInAbstractClasses(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(AbstractStaticLoader::class);

        $loader->loadClassMetadata($metadata);

        $this->assertCount(1, $metadata->getTargetProperties());
    }

    public function testLoadClassMetadataInNonStaticMethods(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(NonStaticLoaderEntity::class);

        $this->expectException(MappingException::class);
        $this->expectExceptionMessage('The method "Guennichi\PropertyLoader\Tests\Mapping\Loader\NonStaticLoaderEntity::loadMetadata()" should be static.');

        $loader->loadClassMetadata($metadata);
    }

    public function testLoadClassMetadataInAbstractStaticMethods(): void
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(AbstractMethodStaticLoader::class);

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }
}

interface StaticLoaderInterface
{
    public static function loadMetadata(ClassMetadata $metadata);
}

abstract class AbstractStaticLoader
{
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyLoader('foo', new Gmail());
    }
}

class NonStaticLoaderEntity
{
    public static ?ClassMetadata $invokedWith = null;

    public function loadMetadata(ClassMetadata $metadata): void
    {
        self::$invokedWith = $metadata;
    }
}

abstract class AbstractMethodStaticLoader
{
    public abstract static function loadMetadata(ClassMetadata $metadata): void;
}

class StaticLoaderEntity
{
    public static ?ClassMetadata $invokedWith = null;

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        self::$invokedWith = $metadata;
    }
}

class StaticLoaderDocument extends BaseStaticLoaderDocument
{
}

class BaseStaticLoaderDocument
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyLoader('foo', new Gmail());
    }
}

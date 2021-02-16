<?php


namespace Guennichi\PropertyLoader\Tests\Mapping\Loader;


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

    public function testLoadClassMetadataReturnsFalseIfNotSuccessful()
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata('\stdClass');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadata()
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderEntity::class);

        $loader->loadClassMetadata($metadata);

        $this->assertEquals(StaticLoaderEntity::$invokedWith, $metadata);
    }


    public function testLoadClassMetadataDoesNotRepeatLoadWithParentClasses()
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

    public function testLoadClassMetadataIgnoresInterfaces()
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(StaticLoaderInterface::class);

        $loader->loadClassMetadata($metadata);

        $this->assertCount(0, $metadata->getTargetProperties());
    }

    public function testLoadClassMetadataInAbstractClasses()
    {
        $loader = new StaticMethodLoader('loadMetadata');
        $metadata = new ClassMetadata(AbstractStaticLoader::class);

        $loader->loadClassMetadata($metadata);

        $this->assertCount(1, $metadata->getTargetProperties());
    }
}

interface StaticLoaderInterface
{
    public static function loadMetadata(ClassMetadata $metadata);
}

abstract class AbstractStaticLoader
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyLoader('foo', new Gmail());
    }
}

class StaticLoaderEntity
{
    public static ?ClassMetadata $invokedWith = null;

    public static function loadMetadata(ClassMetadata $metadata)
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

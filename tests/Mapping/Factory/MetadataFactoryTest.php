<?php


namespace Guennichi\PropertyLoader\Tests\Mapping\Factory;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Mapping\Factory\MetadataFactory;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
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
}

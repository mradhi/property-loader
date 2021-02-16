<?php


namespace Guennichi\PropertyLoader\Tests\Loaders;


use Guennichi\PropertyLoader\Context\ExecutionContextInterface;
use Guennichi\PropertyLoader\Loaders\Source;
use Guennichi\PropertyLoader\Loaders\SourceHandler;
use Guennichi\PropertyLoader\Mapping\ClassMetadata;
use Guennichi\PropertyLoader\Mapping\Loader\StaticMethodLoader;
use Guennichi\PropertyLoader\PropertyLoader;
use PHPUnit\Framework\TestCase;

class SourceHandlerTest extends TestCase
{
    public function testItSetDataForTargetProperty(): void
    {
        $propertyLoader = new PropertyLoader(new StaticMethodLoader(), [
            new EmailHandler()
        ]);

        $propertyLoader->load($entity = new Entity('radhi'));

        $this->assertSame('radhi@guennichi.com', $entity->email);
    }
}

class Entity
{
    public string $name;
    public string $email;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function configurePropertyLoaderMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyLoader('email', new Email(['source' => 'name']));
    }
}

class Email extends Source
{
}

class EmailHandler extends SourceHandler
{
    /**
     * @inheritDoc
     */
    public function getTargetValue($sourceValue, Source $loader, ExecutionContextInterface $context): string
    {
        return $sourceValue . '@guennichi.com';
    }
}

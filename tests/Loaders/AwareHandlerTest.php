<?php


namespace Guennichi\PropertyLoader\Tests\Loaders;


use Doctrine\Common\Annotations\AnnotationReader;
use Guennichi\PropertyLoader\Loaders\AwareHandler;
use Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader;
use Guennichi\PropertyLoader\PropertyLoader;
use Guennichi\PropertyLoader\Tests\Fixtures\Loaders\GmailHandler;
use Guennichi\PropertyLoader\Tests\Fixtures\Person;
use PHPUnit\Framework\TestCase;

class AwareHandlerTest extends TestCase
{
    public function testItHandlesPropertiesWithAwareLoad(): void
    {
        $propertyLoader = new PropertyLoader(new AnnotationLoader(new AnnotationReader()), [
            new AwareHandler(),
            new GmailHandler()
        ]);

        $propertyLoader->load($person = new Person('radhi', 'gaston'));

        $this->assertSame('gaston@gmail.com', $person->relatedPerson->email);
    }
}

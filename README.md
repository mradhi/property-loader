# Dynamic property loader library

A PHP library to dynamically load object properties using custom handlers (useful for DTO's). It comes
with two `ClassMetadata` mapping loaders.

- **StaticMethodLoader**: Load mapping configuration using 
  `configurePropertyLoaderMetadata()` method.
- **AnnotationLoader**: Load mapping configuration using annotations existed on `src/Loaders/` directory

### Installation

The recommended way to install `property-loader` is using
[Composer](https://getcomposer.org/).

```bash
# Install Composer
$ curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of `ibanfirst-sdk`.
```bash
$ composer require guennichi/property-loader
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

### Usage

The entry point of this library is the `Guennichi\PropertyLoader` class.

```php
$mappingLoader = new Guennichi\PropertyLoader\Mapping\Loader\AnnotationLoader(
    new Doctrine\Common\Annotations\AnnotationReader()
);
$handlers = [
    new Guennichi\PropertyLoader\Loaders\AwareHandler(),
    // Add your custom handlers...
];

$propertyLoader = new Guennichi\PropertyLoader\PropertyLoader($mappingLoader, $handlers);
```

#### Create a Custom Property Loader

##### Create the Loader

You can create a custom loader by extending the base abstract loader class, 
`Guennichi\PropertyLoader\Loader`. By doing so, you'll be able to manage
even how your custom loader will load the property inside a given object.

As an example you're going to create a basic 
property loader that dynamically generates an email address inside
a property for a person object.

```php

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Email extends Guennichi\PropertyLoader\Loader {
    // The source property name
    // Which we will use to generate the email
    // based on it's value.
    public string $source;
}
```

##### Create the Loader Handler 

As you can see, a loader class is fairly minimal. 
The actual handler is performed by another “constraint validator” class. 
The constraint validator class is specified 
by the constraint’s `handledBy()` method, which has this default logic:

```php
// in the base Guennichi\PropertyLoader\Loader class
public function handledBy(): string
{
    return static::class.'Handler';
}
```

In other words, if you create a custom Loader (e.g. `Email`), 
PropertyLoader library will automatically look for another class, 
`EmailHandler` when actually performing the loading.

The handler class only has one required method `handle()`:

```php
class EmailHandler extends Guennichi\PropertyLoader\LoaderHandler
{
     /**
     * @inheritDoc
     *
     * @param Email $loader
     */
    public function handle(Guennichi\PropertyLoader\Loader $loader, Guennichi\PropertyLoader\Context\ExecutionContextInterface $context): void
    {
        // Get the sourceProperty reflection object
        // based on "source" (name of property)
        $sourceProperty = $context->getClassMetadata()
        ->getReflectionClass()
        ->getProperty($loader->source);
        
        $object = $context->getObject();

        $value = $sourceProperty->getValue($object) . '@mail.com';

        $context->getTargetPropertyMetadata()->setPropertyValue($value, $object);
    }
}
```

**NOTE:** To reduce complexity of such logic, your handler can extend `Guennichi\Loaders\SourceHandler` class,
and in that case your handler will be something like:

```php
class EmailHandler extends Guennichi\PropertyLoader\Loaders\SourceHandler
{
    /**
     * @inheritDoc
     */
    public function getTargetValue(
        $sourceValue, 
        Guennichi\PropertyLoader\Loaders\Source $loader, 
        Guennichi\PropertyLoader\Context\ExecutionContextInterface $context
    ): string
    {
        return $sourceValue . '@mail.com';
    }
}
```

Inside `handle()`, you don’t need to return a value. 
Instead, you set value for target property metadata existed in the context.

##### Using the new Loader

```php
namespace App\DTO;

use App\Loaders as AcmeLoad;
use Guennichi\PropertyLoader\Loaders as Load;

class Person
{
    // ...

    public string $name;
    
    /**
     * @AcmeLoad\Email(source="name") 
     */
    public string $email;

    /**
     * @Load\Aware
     */
    public SomeOtherObjects $relatedObject;
    // ...
}
```

As you can see here, we added the new custom loader inside the Person class,
in our case, we want to load the property "email" based on the value of the
"name" property.

**@Load\Aware** annotation is used to tell our PropertyLoader library, that we want
to cascade the dynamic load for that custom object too.

Now, it's time to see how things are going for our Person object:

```php
$person = new Person();
$person->name = 'radhi';

// Load properties based on mappings and stored handlers
$propertyLoader->load($person);

echo $person->email; // radhi@mail.com
```

## Supporting PHP >= 7.4

This client library only supports PHP version >= 7.4 , Check [Supported Versions](https://www.php.net/supported-versions.php)
for more information.

## Questions?

If you have any questions please [open an issue](https://github.com/mradhi/property-loader/issues/new).

## License

This library is released under the MIT License. See the bundled [LICENSE](https://github.com/mradhi/property-loader/blob/master/LICENSE) file for details.

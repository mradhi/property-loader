<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures\Loaders;


use Guennichi\PropertyLoader\Loader;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Gmail extends Loader
{
    public string $source;
}

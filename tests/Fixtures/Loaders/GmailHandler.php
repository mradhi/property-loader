<?php


namespace Guennichi\PropertyLoader\Tests\Fixtures\Loaders;


use Guennichi\PropertyLoader\Context\ExecutionContextInterface;
use Guennichi\PropertyLoader\Loaders\Source;
use Guennichi\PropertyLoader\Loaders\SourceHandler;

class GmailHandler extends SourceHandler
{
    /**
     * @inheritDoc
     */
    public function getTargetValue($sourceValue, Source $loader, ExecutionContextInterface $context): string
    {
        return $sourceValue . '@gmail.com';
    }
}

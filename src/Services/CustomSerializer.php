<?php


namespace App\Services;

use Hateoas\Serializer\JsonHalSerializer;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class CustomSerializer extends JsonHalSerializer
{

    public function serializeLinks(array $links, SerializationVisitorInterface $visitor, SerializationContext $context): void
    {
        $serializedLinks = array();
        foreach ($links as $link) {
            $serializedLinks[$link->getRel()] = $link->getHref();
        }
        $visitor->visitProperty(new StaticPropertyMetadata(self::class, '_links', $serializedLinks), $serializedLinks);
    }
}
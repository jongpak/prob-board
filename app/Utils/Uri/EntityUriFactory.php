<?php

namespace App\Utils\Uri;

use App\Entity\Board;
use App\Utils\Uri\Interfaces\ResourceUri;

class EntityUriFactory
{
    /**
     * @return ResourceUri
     */
    public static function getEntityUri($entity)
    {
        switch (get_parent_class($entity) ?: get_class($entity)) {
            case Board::class:
                return new BoardEntityUri($entity);
                break;

            default:
                return new DefaultEntityUri($entity);
                break;
        }
    }

    public static function getEntityUriArray(array $entities)
    {
        $uris = [];

        foreach ($entities as $entity) {
            $uris[$entity->getId()] = self::getEntityUri($entity);
        }

        return $uris;
    }
}

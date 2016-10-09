<?php

namespace App\Utils\Uri;

use App\Utils\Uri\Interfaces\ResourceUri;
use App\Entity\Traits\Identifiable;
use Core\Application;

class DefaultEntityUri implements ResourceUri
{
    /**
     * @var Identifiable
     */
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function create($parameters = [])
    {
        return $this->makeUri(self::CREATE, $parameters);
    }

    public function read($parameters = [])
    {
        return $this->makeUri(self::READ, $parameters);
    }

    public function update($parameters = [])
    {
        return $this->makeUri(self::UPDATE, $parameters);
    }

    public function delete($parameters = [])
    {
        return $this->makeUri(self::DELETE, $parameters);
    }

    protected function makeUri($operation, array $parameters)
    {
        $query = http_build_query($parameters);
        $operation = $operation ?: '';

        return Application::getUrl(sprintf('%s/%s%s%s',
                $this->getEntityName(),
                $this->getIdentifiedName(),
                $operation ? '/' . $operation : '',
                $query ? '?' . $query : ''
            )
        );
    }

    protected function getIdentifiedName()
    {
        return $this->entity->getId();
    }

    protected function getEntityName($entity = null)
    {
        $object = $entity ?: $this->entity;
        $name = explode('\\', is_string($object) ? $object : get_class($object));
        return strtolower($name[count($name) - 1]);
    }
}

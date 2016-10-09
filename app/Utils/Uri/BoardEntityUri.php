<?php

namespace App\Utils\Uri;

use Core\Application;

class BoardEntityUri extends DefaultEntityUri
{
    protected function getIdentifiedName()
    {
        return $this->entity->getName();
    }
}

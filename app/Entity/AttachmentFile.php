<?php

namespace App\Entity;

use App\Entity\Traits\Identifiable;

/**
 * @Entity
 * @Table(name="attachment_files")
 */
class AttachmentFile
{
    use Identifiable;

    /**
     * @Column(type="text", length=255)
     */
    protected $name;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

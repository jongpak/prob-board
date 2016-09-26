<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="attachment_files")
 */
class AttachmentFile
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

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

<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="boards")
 */
class Board
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

    /**
     * @Column(type="text", length=255)
     */
    protected $title;

    public function getTitle()
    {
        return $this->title;
    }
}

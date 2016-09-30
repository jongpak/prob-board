<?php

namespace App\Entity;

use App\Entity\Traits\Identifiable;

/**
 * @Entity
 * @Table(name="boards")
 */
class Board
{
    use Identifiable;

    /**
     * @Column(type="text", length=255)
     */
    protected $name;

    /**
     * @Column(type="text", length=255)
     */
    protected $title;

    /**
     * @Column(type="integer", options={"default": 10})
     */
    protected $listPerPage = 10;


    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getListPerPage()
    {
        return $this->listPerPage;
    }
}

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

    /**
     * @Column(type="integer", options={"default": 10})
     */
    protected $listPerPage = 10;


    public function getId()
    {
        return $this->id;
    }

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

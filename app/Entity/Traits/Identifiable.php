<?php

namespace App\Entity\Traits;

trait Identifiable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}

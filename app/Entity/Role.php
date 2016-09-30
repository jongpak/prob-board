<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\Identifiable;

/**
 * @Entity
 * @Table(name="roles")
 */
class Role
{
    use Identifiable;

    /**
     * @Column(type="string", length=32)
     */
    protected $name;

    /**
     * @var ArrayCollection
     * @ManyToMany(targetEntity="User", mappedBy="roles")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\Identifiable;

/**
 * @Entity
 * @Table(name="permissions")
 */
class Permission
{
    use Identifiable;

    /**
     * @Column(type="string", length=128)
     */
    protected $operation;

    /**
     * @var Role
     * @ManyToMany(targetEntity="Role")
     * @JoinTable(name="role_permissions")
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}

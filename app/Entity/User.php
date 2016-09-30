<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\Identifiable;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    use Identifiable;

    /**
     * @Column(type="string", length=128)
     */
    protected $accountId;

    /**
     * @Column(type="string", length=128)
     */
    protected $password;

    /**
     * @Column(type="string", length=32)
     */
    protected $nickname;

    /**
     * @Column(type="string", length=128)
     */
    protected $email;

    /**
     * @var ArrayCollection
     * @ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="user_roles")
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}

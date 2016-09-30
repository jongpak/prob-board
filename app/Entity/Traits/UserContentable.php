<?php

namespace App\Entity\Traits;

use App\Entity\User;

trait UserContentable
{
    /**
     * @var User
     * @ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @Column(type="string", length=32)
     */
    protected $author;

    /**
     * @Column(type="string", length=128)
     */
    protected $password;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        if ($this->user) {
            return $this->user->getNickname();
        }
        return $this->author;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        if ($this->user) {
            return $this->user->getPassword();
        }
        return $this->password;
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function updateUserInfo()
    {
        if ($this->user) {
            $this->setAuthor($this->user->getNickname());
            $this->setPassword($this->user->getPassword());
        }
    }
}

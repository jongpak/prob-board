<?php

namespace App\Entity;

use \DateTime;

/**
 * @Entity
 * @Table(name="comments")
 */
class Comment
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var Post
     * @ManyToOne(targetEntity="Post")
     * @JoinColumn(name="post", referencedColumnName="id")
     */
    protected $post;

    /**
     * @Column(type="text", length=65535)
     */
    protected $content;

    /**
     * @var User
     * @OneToOne(targetEntity="User")
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

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $updated_at;


    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = $this->created_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt(DateTime $datetime)
    {
        $this->updated_at = $datetime;
    }
}

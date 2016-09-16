<?php

namespace App\Entity;

use \DateTime;

/**
 * @Entity
 * @Table(name="posts")
 */
class Post
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="integer")
     */
    protected $board;

    /**
     * @Column(type="string", length=255)
     */
    protected $subject;

    /**
     * @Column(type="text", length=65535)
     */
    protected $content;

    /**
     * @Column(type="string", length=32)
     */
    protected $author;

    /**
     * @Column(type="string", length=128)
     */
    protected $password;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
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

    public function setBoard($board)
    {
        $this->board = $board;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
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

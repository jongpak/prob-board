<?php

namespace App\Entity;

use \JsonSerializable;

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
     * @Column(type="string")
     */
    protected $title;

    /**
     * @Column(type="text", length=65535)
     */
    protected $content;

    /**
     * @Column(type="text", length=32)
     */
    protected $author;

    /**
     * @Column(type="text", length=128)
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
}

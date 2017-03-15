<?php

namespace App\Entity;

use \DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\Identifiable;
use App\Entity\Traits\UserContentable;
use App\Entity\Traits\Timestampable;
use App\Entity\Traits\FileAttachable;

/**
 * @Entity
 * @Table(name="posts")
 * @HasLifecycleCallbacks
 */
class Post
{
    use Identifiable;
    use UserContentable;
    use Timestampable;
    use FileAttachable;

    /**
     * @var Board
     * @ManyToOne(targetEntity="Board")
     * @JoinColumn(name="board", referencedColumnName="id")
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
     * @Column(type="boolean", nullable=false, options={"default": false})
     */
    protected $is_secret;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Comment", mappedBy="post")
     */
    protected $comments;


    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = $this->created_at;

        $this->comments = new ArrayCollection();
        $this->attachmentFiles = new ArrayCollection();
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

    public function setIsSecret($is_secret)
    {
        $this->is_secret = $is_secret;
    }

    public function getIsSecret()
    {
        return $this->is_secret;
    }

    public function getComments()
    {
        return $this->comments;
    }
}

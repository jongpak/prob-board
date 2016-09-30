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


    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = $this->created_at;

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
}

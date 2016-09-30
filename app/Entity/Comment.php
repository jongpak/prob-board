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
 * @Table(name="comments")
 * @HasLifecycleCallbacks
 */
class Comment
{
    use Identifiable;
    use UserContentable;
    use Timestampable;
    use FileAttachable;

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


    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = $this->created_at;

        $this->attachmentFiles = new ArrayCollection();
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
}

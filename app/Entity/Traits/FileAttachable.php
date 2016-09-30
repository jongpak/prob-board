<?php

namespace App\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\AttachmentFile;

trait FileAttachable
{
    /**
     * @var ArrayCollection
     * @ManyToMany(targetEntity="AttachmentFile")
     */
    protected $attachmentFiles;

    public function addAttachmentFile(AttachmentFile $file)
    {
        $this->attachmentFiles[] = $file;
    }

    public function getAttachemntFile()
    {
        return $this->attachmentFiles;
    }
}

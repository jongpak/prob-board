<?php

namespace App\Service;

use App\Entity\AttachmentFile;
use App\Exception\EntityNotFound;
use Core\Utils\EntityFinder;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SplFileObject;

class AttachmentService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAttachmentFileEntity($id)
    {
        $attachment = EntityFinder::findById(AttachmentFile::class, $id);

        if ($attachment === null) {
            throw new EntityNotFound('Attachment is not found!');
        }

        return $attachment;
    }

    public function getAttachmentFileInfo(AttachmentFile $attachmentFile)
    {
        try {
            return new SplFileObject(__DIR__ . '/../../data/attachment/' . $attachmentFile->getId());
        } catch (Exception $e) {
            throw new EntityNotFound('Attachment file is not exists or deleted');
        }
    }
}
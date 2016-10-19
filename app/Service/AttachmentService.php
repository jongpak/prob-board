<?php

namespace App\Service;

use App\Entity\AttachmentFile;
use App\Exception\EntityNotFound;
use Core\Utils\EntityFinder;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Http\Message\UploadedFileInterface;
use SplFileObject;

class AttachmentService
{
    const FILE_PATH = __DIR__ . '/../../data/attachment/';
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
            return new SplFileObject(self::FILE_PATH . $attachmentFile->getId());
        } catch (Exception $e) {
            throw new EntityNotFound('Attachment file is not exists or deleted');
        }
    }

    public function uploadFile(array $uploadFiles)
    {
        $uploadedFileEntity = [];

        /** @var UploadedFileInterface $uploadFile */
        foreach ($uploadFiles as $uploadFile) {
            if ($uploadFile->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            $attachmentFile = new AttachmentFile();
            $attachmentFile->setName($uploadFile->getClientFilename());

            $this->entityManager->persist($attachmentFile);
            $this->entityManager->flush();

            $uploadFile->moveTo(self::FILE_PATH . $attachmentFile->getId());

            $uploadedFileEntity[] = $attachmentFile;
        }

        return $uploadedFileEntity;
    }

    public function deleteFile(array $deleteFiles)
    {
        foreach ($deleteFiles as $deleteFileId) {
            /** @var AttachmentFile */
            $attachmentFile = $this->entityManager->getRepository(AttachmentFile::class)->find($deleteFileId);

            unlink(self::FILE_PATH . $attachmentFile->getId());
            $this->entityManager->remove($attachmentFile);
        }

        $this->entityManager->flush();
    }
}
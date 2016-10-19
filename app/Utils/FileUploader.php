<?php

namespace App\Utils;

use Core\DatabaseManager;
use App\Entity\AttachmentFile;
use Psr\Http\Message\UploadedFileInterface;

class FileUploader
{
    const FILE_PATH =  __DIR__ . '/../../data/attachment/';

    public static function uploadFiles(array $uploadFiles)
    {
        $result = [];
        $entityManager = DatabaseManager::getEntityManager();

        /** @var UploadedFileInterface $uploadFile */
        foreach ($uploadFiles as $uploadFile) {
            if ($uploadFile->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            $attachmentFile = new AttachmentFile();
            $attachmentFile->setName($uploadFile->getClientFilename());

            $entityManager->persist($attachmentFile);
            $entityManager->flush();

            $uploadFile->moveTo(self::FILE_PATH . $attachmentFile->getId());

            $result[] = $attachmentFile;
        }

        return $result;
    }
}

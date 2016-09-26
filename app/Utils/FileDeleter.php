<?php

namespace App\Utils;

use Core\DatabaseManager;
use App\Entity\AttachmentFile;

class FileDeleter
{
    const FILE_PATH =  __DIR__ . '/../../data/attachment/';

    public static function deleteFiles(array $deleteFiles)
    {
        $entityManager = DatabaseManager::getEntityManager();

        foreach ($deleteFiles as $deleteFileId) {
            /** @var AttachmentFile */
            $attachmentFile = $entityManager->getRepository(AttachmentFile::class)->find($deleteFileId);

            unlink(self::FILE_PATH . $attachmentFile->getId());
            $entityManager->remove($attachmentFile);
        }

        $entityManager->flush();
    }
}

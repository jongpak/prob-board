<?php

namespace App\Utils;

use App\Entity\Traits\FileAttachable;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManagerInterface;

class AttachmentFileUtil
{
    /**
     * @param $content FileAttachable
     * @param array $files
     * @param EntityManagerInterface $entityManager
     * @return array
     */
    public static function uploadFiles($content, array $files, EntityManagerInterface $entityManager)
    {
        $attachmentService = new AttachmentService($entityManager);
        $files = $attachmentService->uploadFile($files);

        foreach ($files as $file) {
            $content->addAttachmentFile($file);
        }
        $entityManager->flush();

        return $files;
    }

    /**
     * @param $content FileAttachable
     * @param array $fileId
     * @param EntityManagerInterface $entityManager
     */
    public static function deleteFiles($content, array $fileId, EntityManagerInterface $entityManager)
    {
        $attachmentService = new AttachmentService($entityManager);
        $attachmentService->deleteFile($fileId);
    }
}
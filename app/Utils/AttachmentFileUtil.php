<?php

namespace App\Utils;

use App\Entity\Traits\FileAttachable;
use App\Service\AttachmentService;
use Core\Utils\EntityUtils\EntityUpdate;

class AttachmentFileUtil
{
    /**
     * @param $content FileAttachable
     * @param array $files
     * @return array
     */
    public static function uploadFiles($content, array $files)
    {
        $attachmentService = new AttachmentService();
        $files = $attachmentService->uploadFile($files);

        foreach ($files as $file) {
            $content->addAttachmentFile($file);
        }
        EntityUpdate::update($content);

        return $files;
    }

    /**
     * @param $content FileAttachable
     * @param array $fileId
     */
    public static function deleteFiles($content, array $fileId)
    {
        $attachmentService = new AttachmentService();
        $attachmentService->deleteFile($fileId);
    }
}
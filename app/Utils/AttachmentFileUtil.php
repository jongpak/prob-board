<?php

namespace App\Utils;

use App\Entity\AttachmentFile;
use App\Entity\Traits\FileAttachable;
use App\Service\AttachmentService;
use Core\Utils\EntityUtils\EntityUpdate;
use SplFileInfo;

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
        $attachments = $content->getAttachemntFiles();
        $attachmentIds = [];

        foreach($attachments as $attachment) {
            $attachmentIds[] = $attachment->getId();
        }

        foreach($fileId as $id) {
            $fileKey = array_search($id, $attachmentIds);
            if($fileKey === false) {
                unset($fileId[$fileKey]);
            }
        }

        $attachmentService = new AttachmentService();
        $attachmentService->deleteFile($fileId);
    }

    /**
     * @param AttachmentFile $attachmentFile
     * @return int byte size of $attachmentFile
     */
    public static function getSize(AttachmentFile $attachmentFile) {
        $attachmentService = new AttachmentService();
        return $attachmentService->getAttachmentFileInfo($attachmentFile)->getSize();
    }
}
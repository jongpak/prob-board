<?php

namespace App\Service;

use App\Entity\AttachmentFile;
use App\Exception\EntityNotFound;
use Core\Utils\EntityUtils\EntityDelete;
use Core\Utils\EntityUtils\EntityInsert;
use Core\Utils\EntityUtils\EntitySelect;
use Exception;
use Psr\Http\Message\UploadedFileInterface;
use SplFileObject;

class AttachmentService
{
    const FILE_PATH = __DIR__ . '/../../data/attachment/';

    public function getAttachmentFileEntity($id)
    {
        $attachment = EntitySelect::select(AttachmentFile::class)->findById($id);

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

            EntityInsert::insert($attachmentFile);

            $uploadFile->moveTo(self::FILE_PATH . $attachmentFile->getId());

            $uploadedFileEntity[] = $attachmentFile;
        }

        return $uploadedFileEntity;
    }

    public function deleteFile(array $deleteFiles)
    {
        foreach ($deleteFiles as $deleteFileId) {
            /** @var AttachmentFile */
            $attachmentFile = EntitySelect::select(AttachmentFile::class)->findById($deleteFileId);

            unlink(self::FILE_PATH . $attachmentFile->getId());
            EntityDelete::delete($attachmentFile);
        }
    }
}
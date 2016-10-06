<?php

namespace App\Controller;

use App\Entity\AttachmentFile;
use Core\Utils\EntityFinder;
use \Exception;

class Attachment
{
    public function index($id)
    {
        /** @var AttachmentFile */
        $attachment = EntityFinder::findById(AttachmentFile::class, $id);

        if ($attachment === null) {
            throw new Exception('Attachment is not found!');
        }

        $fileFullPath = __DIR__ . '/../data/attachment/' . $attachment->getId();

        if (file_exists($fileFullPath) === false) {
            throw new Exception('Attachment file is not exists or deleted');
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $attachment->getName() . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fileFullPath));

        ob_clean();
        flush();
        readfile($fileFullPath);
    }
}

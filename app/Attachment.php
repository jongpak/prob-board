<?php

namespace App\Controller;

use App\Entity\AttachmentFile;
use Core\Utils\EntityFinder;

class Attachment
{
    public function index($id)
    {
        /** @var AttachmentFile */
        $attachment = EntityFinder::findById(AttachmentFile::class, $id);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $attachment->getName() . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize(__DIR__ . '/../data/attachment/' . $attachment->getId()));

        ob_clean();
        flush();
        readfile(__DIR__ . '/../data/attachment/' . $attachment->getId());
    }
}

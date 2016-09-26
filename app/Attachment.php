<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AttachmentFile;

class Attachment
{
    public function index($id, EntityManagerInterface $entityManage)
    {
        /** @var AttachmentFile */
        $attachment = $entityManage->getRepository(AttachmentFile::class)->find($id);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $attachment->getName() . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize(__DIR__ . '/../data/attachment/' . $attachment->getId()));

        ob_clean();
        flush();
        readfile(__DIR__ . '/../data/attachment/' . $attachment->getId());
    }
}

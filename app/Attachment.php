<?php

namespace App\Controller;

use App\Entity\AttachmentFile;
use App\Exception\EntityNotFound;
use Core\Utils\EntityFinder;
use Core\Utils\ResponseProxy;
use Zend\Diactoros\Response\EmptyResponse;
use \Exception;
use \SplFileObject;

class Attachment
{
    public function index($id, ResponseProxy $response)
    {
        /** @var AttachmentFile */
        $attachment = EntityFinder::findById(AttachmentFile::class, $id);

        if ($attachment === null) {
            throw new EntityNotFound('Attachment is not found!');
        }

        try {
            $file = new SplFileObject(__DIR__ . '/../data/attachment/' . $attachment->getId());
        } catch (Exception $e) {
            throw new EntityNotFound('Attachment file is not exists or deleted');
        }

        $response->setResponse(
            new EmptyResponse(
                200,
                [
                    'Content-Type' => mime_content_type($file->getPathname()),
                    'Content-Disposition' => 'inline; filename="' . $attachment->getName() . '"',
                    'Content-Transfer-Encoding' => 'binary',
                    'Content-Length' => $file->getSize()
                ]
            )
        );

        ob_start();
        echo $file->openFile()->fread($file->getSize());
    }
}

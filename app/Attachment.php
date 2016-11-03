<?php

namespace App\Controller;

use App\Service\AttachmentService;
use Core\Utils\ResponseProxy;
use Sinergi\BrowserDetector\Browser;
use Zend\Diactoros\Response\EmptyResponse;

class Attachment
{
    /**
     * @var AttachmentService
     */
    private $attachmentService;

    public function __construct()
    {
        $this->attachmentService = new AttachmentService();
    }

    public function index($id, ResponseProxy $response)
    {
        $attachmentFile = $this->attachmentService->getAttachmentFileEntity($id);
        $file = $this->attachmentService->getAttachmentFileInfo($attachmentFile);

        $isIE = (new Browser())->getName() === Browser::IE;
        $fileNameHeader = ($isIE ? rawurlencode($attachmentFile->getName()) : $attachmentFile->getName());

        $response->setResponse(
            new EmptyResponse(
                200,
                [
                    'Content-Type' => mime_content_type($file->getPathname()),
                    'Content-Disposition' => 'inline; filename="' . $fileNameHeader . '"',
                    'Content-Transfer-Encoding' => 'binary',
                    'Content-Length' => $file->getSize()
                ]
            )
        );

        ob_start();
        echo $file->openFile()->fread($file->getSize());
    }
}

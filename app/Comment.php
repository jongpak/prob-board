<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Utils\FileDeleter;
use App\Utils\FileUploader;
use App\Utils\ContentUserInfoSetter;
use App\Utils\FormUtility;
use Core\Utils\EntityFinder;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use \DateTime;

class Comment
{
    /**
     * @var CommentModel;
     */
    private $comment;

    public function __construct($id)
    {
        $this->comment = EntityFinder::findById(CommentModel::class, $id);
    }

    public function showEditForm(ViewModel $viewModel)
    {
        $viewModel->set('comment', $this->comment);
        return 'default/commentForm';
    }

    public function edit($id, $parsedBody, ServerRequestInterface $req, EntityManagerInterface $entityManager, LoginManagerInterface $loginManager)
    {
        $this->comment->setContent($parsedBody['content']);
        $this->comment->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($this->comment, $parsedBody, $loginManager);

        $files = FileUploader::uploadFiles($req->getUploadedFiles()['file']);
        foreach ($files as $file) {
            $this->comment->addAttachmentFile($file);
        }

        FileDeleter::deleteFiles(FormUtility::getCheckboxOnItem('delete-file', $parsedBody));

        $entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->comment->getPost()->getId());
    }
}

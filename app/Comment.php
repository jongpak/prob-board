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
use App\Utils\Uri\EntityUriFactory;
use Core\Utils\EntityFinder;
use App\Auth\LoginManagerInterface;
use App\Exception\EntityNotFound;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use \DateTime;

class Comment
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CommentModel
     */
    private $comment;

    public function __construct($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->entityManager = $entityManager;
        $this->comment = EntityFinder::findById(CommentModel::class, $id);

        if ($this->comment === null) {
            throw new EntityNotFound('Comment is not found');
        }

        $viewModel->set('comment', $this->comment);
    }

    public function showEditForm(ViewModel $viewModel)
    {
        return 'default/commentForm';
    }

    public function edit($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $this->comment->setContent($parsedBody['content']);
        $this->comment->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($this->comment, $parsedBody, $loginManager);

        $files = FileUploader::uploadFiles($req->getUploadedFiles()['file']);
        foreach ($files as $file) {
            $this->comment->addAttachmentFile($file);
        }

        FileDeleter::deleteFiles(FormUtility::getCheckboxOnItem('delete-file', $parsedBody));

        $this->entityManager->flush();

        return 'redirect: ' . EntityUriFactory::getEntityUri($this->comment->getPost())->read();
    }

    public function showDeleteForm(ViewModel $viewModel)
    {
        return 'default/delete';
    }

    public function delete()
    {
        $this->comment->setPost(null);
        $this->entityManager->flush();

        return 'redirect:' . EntityUriFactory::getEntityUri($this->comment->getPost())->read();
    }
}

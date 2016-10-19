<?php

namespace App\Controller;

use App\Service\CommentService;
use App\Utils\AttachmentFileUtil;
use Core\ViewModel;
use App\Entity\Comment as CommentModel;
use App\Utils\FormUtility;
use App\Utils\Uri\EntityUriFactory;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Comment
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var CommentModel
     */
    private $comment;

    public function __construct($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->commentService = new CommentService($entityManager);
        $this->comment = $this->commentService->getCommentEntity($id);

        $viewModel->set('comment', $this->comment);
    }

    public function showEditForm(ViewModel $viewModel)
    {
        return 'default/commentForm';
    }

    public function edit($parsedBody, ServerRequestInterface $req, EntityManagerInterface $entityManager, LoginManagerInterface $loginManager)
    {
        $this->commentService->editComment($this->comment, $parsedBody, $loginManager);
        AttachmentFileUtil::uploadFiles($this->comment, $req->getUploadedFiles()['file'], $entityManager);
        AttachmentFileUtil::deleteFiles($this->comment, FormUtility::getCheckboxOnItem('delete-file', $parsedBody), $entityManager);

        return 'redirect: ' . EntityUriFactory::getEntityUri($this->comment->getPost())->read();
    }

    public function showDeleteForm(ViewModel $viewModel)
    {
        return 'default/delete';
    }

    public function delete()
    {
        $post = $this->comment->getPost();
        $this->commentService->deleteComment($this->comment);

        return 'redirect:' . EntityUriFactory::getEntityUri($post)->read();
    }
}

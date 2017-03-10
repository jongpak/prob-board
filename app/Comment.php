<?php

namespace App\Controller;

use App\Auth\HashManager;
use App\EventListener\Auth\Exception\PermissionDenied;
use App\Service\CommentService;
use App\Utils\AttachmentFileUtil;
use Core\ViewModel;
use App\Entity\Comment as CommentModel;
use App\Utils\FormUtility;
use App\Utils\Uri\EntityUriFactory;
use App\Auth\LoginManagerInterface;
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

    public function __construct($id, ViewModel $viewModel)
    {
        $this->commentService = new CommentService();
        $this->comment = $this->commentService->getCommentEntity($id);

        $viewModel->set('comment', $this->comment);
    }

    public function showEditConfirm($parsedBody, ViewModel $viewModel)
    {
        if($this->comment->getUser() == null) {
            return 'passwordConfirm';
        }

        if($this->comment->getUser()->getAccountId() == $loginManager->getLoggedAccountId()) {
            return 'redirect:' . EntityUriFactory::getEntityUri($this->comment)->update();
        }
    }

    public function submitEditConfirm($parsedBody, ViewModel $viewModel)
    {
        if(HashManager::getProvider()->isEqualValueAndHash($parsedBody['password'], $this->comment->getPassword()) == false) {
            throw new PermissionDenied('Password is not equal');
        }

        $_SESSION['confirm'] = true;
        return 'redirect:' . EntityUriFactory::getEntityUri($this->comment)->update();
    }

    public function showEditForm(LoginManagerInterface $loginManager, ViewModel $viewModel)
    {
        if(isset($_SESSION['confirm'])) {
            unset($_SESSION['confirm']);
            return 'commentForm';
        }

        if($this->comment->getUser() == null) {
            return 'redirect:' . EntityUriFactory::getEntityUri($this->comment)->update() . '/confirm';
        }

        if($this->comment->getUser()->getAccountId() != $loginManager->getLoggedAccountId()) {
            new PermissionDenied('Permission denied for editing this comment');
        }

        return 'commentForm';
    }

    public function edit($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $this->commentService->editComment($this->comment, $parsedBody, $loginManager);
        AttachmentFileUtil::uploadFiles($this->comment, $req->getUploadedFiles()['file']);
        AttachmentFileUtil::deleteFiles($this->comment, FormUtility::getCheckboxOnItem('delete-file', $parsedBody));

        return 'redirect: ' . EntityUriFactory::getEntityUri($this->comment->getPost())->read();
    }

    public function showDeleteForm(ViewModel $viewModel)
    {
        return 'delete';
    }

    public function delete()
    {
        $post = $this->comment->getPost();
        $this->commentService->deleteComment($this->comment);

        return 'redirect:' . EntityUriFactory::getEntityUri($post)->read();
    }
}

<?php

namespace App\Controller;

use App\Auth\HashManager;
use App\EventListener\Auth\Exception\PermissionDenied;
use App\Utils\AttachmentFileUtil;
use Core\ViewModel;
use App\Service\PostService;
use App\Service\CommentService;
use App\Entity\Post as PostModel;
use App\Utils\FormUtility;
use App\Utils\Uri\EntityUriFactory;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Post
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var PostModel
     */
    private $post;

    public function __construct($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->postService = new PostService();
        $this->commentService = new CommentService();

        $this->post = $this->postService->getPostEntity($id);

        $viewModel->set('post', $this->post);
        $viewModel->set('page', $this->postService->getPageOfPost($this->post, $entityManager));
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('comments', $this->post->getComments());
        return 'post';
    }

    public function showEditForm(LoginManagerInterface $loginManager, ViewModel $viewModel)
    {
        $viewModel->set('afterAction', 'confirm');

        if($this->post->getUser() == null) {
            return 'passwordConfirm';
        }

        if($this->post->getUser()->getAccountId() != $loginManager->getLoggedAccountId()) {
            new PermissionDenied('Permission denied for editing this post');
        }

        return 'postingForm';
    }

    public function editConfirm($parsedBody, ViewModel $viewModel) {
        if(HashManager::getProvider()->isEqualValueAndHash($parsedBody['password'], $this->post->getPassword()) == false) {
            throw new PermissionDenied('Password is not equal');
        }

        $viewModel->set('action', 'edit');

        return 'postingForm';
    }

    public function edit($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $this->postService->editPost($this->post, $parsedBody, $loginManager);
        AttachmentFileUtil::uploadFiles($this->post, $req->getUploadedFiles()['file']);
        AttachmentFileUtil::deleteFiles($this->post, FormUtility::getCheckboxOnItem('delete-file', $parsedBody));

        return 'redirect: ' . EntityUriFactory::getEntityUri($this->post)->read();
    }

    public function showDeleteForm(ViewModel $viewModel)
    {
        return 'delete';
    }

    public function delete()
    {
        $board = $this->post->getBoard();
        $this->postService->deletePost($this->post);

        return 'redirect:' . EntityUriFactory::getEntityUri($board)->read();
    }

    public function writeComment($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $comment = $this->commentService->writeComment($this->post, $parsedBody, $loginManager);
        AttachmentFileUtil::uploadFiles($comment, $req->getUploadedFiles()['file']);

        return 'redirect: ' . EntityUriFactory::getEntityUri($this->post)->read();
    }
}

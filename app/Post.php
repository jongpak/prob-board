<?php

namespace App\Controller;

use App\Utils\AttachmentFileUtil;
use Core\ViewModel;
use App\Service\PostService;
use App\Service\CommentService;
use App\Entity\Post as PostModel;
use App\Utils\FormUtility;
use App\Utils\Uri\EntityUriFactory;
use App\Auth\LoginManagerInterface;
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

    public function __construct($id, ViewModel $viewModel)
    {
        $this->postService = new PostService();
        $this->commentService = new CommentService();

        $this->post = $this->postService->getPostEntity($id);

        $viewModel->set('post', $this->post);
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('comments', $this->post->getComments());
        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        return 'default/postingForm';
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
        return 'default/delete';
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

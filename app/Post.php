<?php

namespace App\Controller;

use App\Auth\HashManager;
use App\EventListener\Auth\Exception\PermissionDenied;
use App\Utils\AttachmentFileUtil;
use App\Utils\SearchQueryUtil;
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

    public function __construct($id, ViewModel $viewModel)
    {
        $this->postService = new PostService();
        $this->commentService = new CommentService();

        $this->post = $this->postService->getPostEntity($id);

        $viewModel->set('post', $this->post);
    }

    public function index(ServerRequestInterface $req, ViewModel $viewModel, EntityManagerInterface $entityManager, LoginManagerInterface $loginManager)
    {
        if($this->hasPermissionOfRead($loginManager) === false) {
            return 'redirect:' . EntityUriFactory::getEntityUri($this->post)->read() . '/confirm';
        }
        unset($_SESSION['read-auth-' . $this->post->getId()]);

        $searchKeyword = SearchQueryUtil::getSearchKeyword($req->getQueryParams());
        $searchType = SearchQueryUtil::getSearchType($req->getQueryParams());
        
        $page = $this->postService->getPageOfPost($this->post, $entityManager, $searchKeyword, $searchType);

        $viewModel->set('comments', $this->post->getComments());
        $viewModel->set('page', $page);
        $viewModel->set('searchQuery', SearchQueryUtil::getKeywordQuery($searchKeyword, $searchType) + ($page > 1 ? ['page' => $page] : []));

        return 'post';
    }

    private function hasPermissionOfRead(LoginManagerInterface $loginManager)
    {
        if($this->post->getIsSecret() === false) {
            return true;
        }

        if(isset($_SESSION['read-auth-' . $this->post->getId()]) === true) {
            return true;
        }

        if($this->post->getUser() !== null) {
            return $this->post->getUser()->getAccountId() === $loginManager->getLoggedAccountId();
        }

        return false;
    }

    public function showReadConfirm($parsedBody, ViewModel $viewModel)
    {
        if($this->post->getUser() == null) {
            return 'passwordConfirm';
        }

        throw new PermissionDenied('Permission denied for reading this post');
    }

    public function submitReadConfirm($parsedBody, ViewModel $viewModel)
    {
        if(HashManager::getProvider()->isEqualValueAndHash($parsedBody['password'], $this->post->getPassword()) == false) {
            throw new PermissionDenied('Password is not equal');
        }

        $_SESSION['read-auth-' . $this->post->getId()] = true;
        return 'redirect:' . EntityUriFactory::getEntityUri($this->post)->read();
    }

    public function showEditConfirm($parsedBody, ViewModel $viewModel)
    {
        if($this->post->getUser() == null) {
            return 'passwordConfirm';
        }
    }

    public function submitEditConfirm($parsedBody, ViewModel $viewModel)
    {
        if(HashManager::getProvider()->isEqualValueAndHash($parsedBody['password'], $this->post->getPassword()) == false) {
            throw new PermissionDenied('Password is not equal');
        }

        $_SESSION['confirm'] = true;
        return 'redirect:' . EntityUriFactory::getEntityUri($this->post)->update();
    }

    public function showEditForm(LoginManagerInterface $loginManager, ViewModel $viewModel)
    {
        if(isset($_SESSION['confirm'])) {
            unset($_SESSION['confirm']);
            return 'postingForm';
        }

        if($this->post->getUser() == null) {
            return 'redirect:' . EntityUriFactory::getEntityUri($this->post)->update() . '/confirm';
        }

        if($this->post->getUser()->getAccountId() != $loginManager->getLoggedAccountId()) {
            new PermissionDenied('Permission denied for editing this post');
        }

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

<?php

namespace App\Controller;

use App\Service\BoardService;
use App\Service\PostService;
use App\Utils\AttachmentFileUtil;
use Core\Utils\EntityUtils\EntitySelect;
use Core\ViewModel;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Utils\Pager;
use App\Utils\Uri\EntityUriFactory;
use App\Auth\LoginManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Board
{
    /**
     * @var BoardService
     */
    private $boardService;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var BoardModel
     */
    private $board;

    public function __construct($name, ViewModel $viewModel)
    {
        $this->boardService = new BoardService();
        $this->postService = new PostService();
        $this->board = $this->boardService->getBoardEntity($name);

        $viewModel->set('board', $this->board);
    }

    public function index(ServerRequestInterface $req, ViewModel $viewModel)
    {
        $page = isset($req->getQueryParams()['page']) ? $req->getQueryParams()['page'] : 1;
        $searchKeyword = isset($req->getQueryParams()['q']) ? $req->getQueryParams()['q'] : '';

        $viewModel->set('posts', $this->boardService->getPosts($this->board, $page, $searchKeyword));
        $viewModel->set('pager', $this->getPager($page));
        $viewModel->set('searchKeyword', $searchKeyword);

        return 'postList';
    }

    public function showPostingForm(ViewModel $viewModel)
    {
        return 'postingForm';
    }

    public function writePost($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $post = $this->postService->writePost($this->board, $parsedBody, $loginManager);
        AttachmentFileUtil::uploadFiles($post, $req->getUploadedFiles()['file']);

        return 'redirect: ' . EntityUriFactory::getEntityUri($post)->read();
    }

    private function getPager($page)
    {
        return (new Pager())
            ->setCurrentPage($page)
            ->setListPerPage($this->board->getListPerPage())
            ->setLinkFactoryFunction($this->getLinkFactory())
            ->getPageNavigation(PostModel::class)
        ;
    }

    private function getLinkFactory()
    {
        return function ($page) {
            return EntityUriFactory::getEntityUri($this->board)->read($page > 1 ? ['page' => $page] : []);
        };
    }
}

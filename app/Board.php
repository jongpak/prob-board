<?php

namespace App\Controller;

use App\Service\BoardService;
use App\Service\PostService;
use Core\ViewModel;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Utils\Pager;
use App\Utils\FileUploader;
use App\Utils\Uri\EntityUriFactory;
use Core\Utils\EntityFinder;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Board
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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

    public function __construct($name, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->entityManager = $entityManager;
        $this->boardService = new BoardService($entityManager);
        $this->postService = new PostService($entityManager);
        $this->board = $this->boardService->getBoardEntity($name);

        $viewModel->set('board', $this->board);
    }

    public function index(ServerRequestInterface $req, ViewModel $viewModel)
    {
        $page = isset($req->getQueryParams()['page']) ? $req->getQueryParams()['page'] : 1;

        $viewModel->set('posts', $this->getPosts($page));
        $viewModel->set('pager', $this->getPager($page));

        return 'default/postList';
    }

    public function showPostingForm(ViewModel $viewModel)
    {
        return 'default/postingForm';
    }

    public function write($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $post = $this->postService->writePost($this->board, $parsedBody, $loginManager);
        $this->postService->attachFile($post, $req->getUploadedFiles()['file']);

        return 'redirect: ' . EntityUriFactory::getEntityUri($post)->read();
    }

    /**
     * @return array
     */
    private function getPosts($page)
    {
        return EntityFinder::findOrderedAndLimitedBy(
            PostModel::class,
            ['board' => $this->board->getId()],
            ['id' => 'DESC'],
            $this->board->getListPerPage(),
            $this->board->getListPerPage() * ($page - 1)
        );
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

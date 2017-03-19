<?php

namespace App\Controller;

use App\Service\BoardService;
use App\Service\PostService;
use App\Utils\AttachmentFileUtil;
use App\Utils\SearchQueryUtil;
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

    public function index(ServerRequestInterface $req, ViewModel $viewModel, LoginManagerInterface $loginManager)
    {
        $page = isset($req->getQueryParams()['page']) ? $req->getQueryParams()['page'] : 1;
        $searchKeyword = SearchQueryUtil::getSearchKeyword($req->getQueryParams());
        $searchType = SearchQueryUtil::getSearchType($req->getQueryParams());

        $viewModel->set('posts', $this->boardService->getPosts($this->board, $page, $searchKeyword, $searchType, $loginManager->getLoggedAccountId()));
        $viewModel->set('pager', $this->getPager($page, $searchKeyword, $searchType, $loginManager->getLoggedAccountId()));
        $viewModel->set('searchQuery', SearchQueryUtil::getKeywordQuery($searchKeyword, $searchType));

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

    private function getPager($page, $searchKeyword, $searchType, $targetAccountId)
    {
        $pager = (new Pager())
            ->setCurrentPage($page)
            ->setListPerPage($this->board->getListPerPage())
            ->setLinkFactoryFunction($this->getLinkFactory($searchKeyword, $searchType));

        if($searchKeyword == null || count($searchType) == 0) {
            return $pager->getPageNavigationByEntityModel(PostModel::class, 'e.board = '.$this->board->getId());
        } else {
            return $pager->getPageNavigationByQueryBuilder(
                $this->boardService->getPostsQueryBuilderByKeyword($this->board, $page, $searchKeyword, $searchType, $targetAccountId)
            );
        }
    }

    private function getLinkFactory($searchKeyword, $searchType)
    {
        $keywordQuery = SearchQueryUtil::getKeywordQuery($searchKeyword, $searchType);

        return function ($page) use ($keywordQuery) {
            return EntityUriFactory::getEntityUri($this->board)
                ->read(($page > 1 ? ['page' => $page] : []) + $keywordQuery);
        };
    }
}

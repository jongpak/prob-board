<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\User as UserModel;
use App\Entity\Board as BoardModel;
use App\Utils\Pager;
use App\Utils\FileUploader;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityFinder;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Exception;

class Board
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var BoardModel
     */
    private $board;

    public function __construct($name, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->entityManager = $entityManager;
        $this->board = EntityFinder::findOneBy(BoardModel::class, ['name' => $name]);

        if ($this->board === null) {
            throw new Exception('[' . $name . '] Board is not found');
        }

        $viewModel->set('board', $this->board);
    }

    public function index(ServerRequestInterface $req, ViewModel $viewModel)
    {
        $page = isset($req->getQueryParams()['page']) ? $req->getQueryParams()['page'] : 1;

        $viewModel->set('posts', $this->getPosts($page));
        $viewModel->set('pager', (new Pager())
            ->setCurrentPage($page)
            ->setListPerPage($this->board->getListPerPage())
            ->setLinkFactoryFunction($this->getLinkFactory())
            ->getPageNavigation(PostModel::class)
        );

        return 'default/postList';
    }

    public function showPostingForm(ViewModel $viewModel)
    {
        return 'default/postingForm';
    }

    public function write($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $post = new PostModel();
        $post->setBoard($this->board);
        $post->setSubject($parsedBody['subject']);
        $post->setContent($parsedBody['content']);
        ContentUserInfoSetter::fillUserInfo($post, $parsedBody, $loginManager);

        $files = FileUploader::uploadFiles($req->getUploadedFiles()['file']);
        foreach ($files as $file) {
            $post->addAttachmentFile($file);
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $post->getId());
    }

    /**
     * @return array
     */
    private function getPosts($page)
    {
        return $this->entityManager->getRepository(PostModel::class)
                ->findBy(
                    ['board' => $this->board->getId()], ['id' => 'DESC'],
                    $this->board->getListPerPage(),
                    $this->board->getListPerPage() * ($page - 1)
                );
    }

    private function getLinkFactory()
    {
        return function ($page) {
            return $page == 1
                ? Application::getUrl('/' . $this->board->getName())
                : Application::getUrl('/' . $this->board->getName() . '?page=' . $page);
        };
    }
}

<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\User as UserModel;
use App\Entity\Board as BoardModel;
use App\Utils\FileUploader;
use App\Utils\ContentUserInfoSetter;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Psr\Http\Message\ServerRequestInterface;

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

    public function __construct($name, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->board = $entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
    }

    public function index(ServerRequestInterface $req, ViewModel $viewModel)
    {
        $page = isset($req->getQueryParams()['page']) ? $req->getQueryParams()['page'] : 1;

        $viewModel->set('board', $this->board);
        $viewModel->set('posts', $this->getPosts($page));
        $viewModel->set('pager', $this->getPagerRender($page));

        return 'default/postList';
    }

    public function showPostingForm(ViewModel $viewModel)
    {
        $viewModel->set('board', $this->board);

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

    private function getPagerRender($page)
    {
        $pagerAdapter = new DoctrineORMAdapter($this->entityManager->createQueryBuilder()
            ->select('post')
            ->from(PostModel::class, 'post')
        );
        $pager = new Pagerfanta($pagerAdapter);
        $pager->setMaxPerPage($this->board->getListPerPage());
        $pager->setCurrentPage($page);

        $pagerView = new TwitterBootstrap3View();
        return $pagerView->render($pager, function ($page) {
            if ($page == 1) {
                return Application::getUrl('/' . $this->board->getName());
            }
            return Application::getUrl('/' . $this->board->getName() . '?page=' . $page);
        });
    }
}

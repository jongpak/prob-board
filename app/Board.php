<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use Core\DatabaseManager;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;

class Board
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = DatabaseManager::getEntityManager();
    }

    public function index($name, ViewModel $viewModel)
    {
        $board = $this->getBoard($name);
        $posts = $this->getPosts($board);

        $viewModel->set('board', $board);
        $viewModel->set('posts', $posts);

        return 'default/postList';
    }

    public function showPostingForm($name, ViewModel $viewModel)
    {
        $board = $this->getBoard($name);
        $viewModel->set('board', $board);

        return 'default/postWriteForm';
    }

    public function writePost($name, ServerRequestInterface $request)
    {
        $board = $this->getBoard($name);

        $post = new PostModel();
        $post->setBoard($board->getId());
        $post->setSubject($request->getParsedBody()['subject']);
        $post->setContent($request->getParsedBody()['content']);
        $post->setAuthor($request->getParsedBody()['author']);
        $post->setPassword($request->getParsedBody()['password']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getInstance()->url('/post/' . $post->getId());
    }

    /**
     * @param  string $name name of board
     * @return BoardModel
     */
    private function getBoard($name)
    {
        return $this->entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
    }

    /**
     * @return array
     */
    private function getPosts(BoardModel $board)
    {
        return $this->entityManager->getRepository(PostModel::class)->findBy(['board' => $board->getId()]);
    }
}

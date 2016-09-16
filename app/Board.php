<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManagerInterface;

class Board
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

    public function writePost($name, $parsedBody)
    {
        $board = $this->getBoard($name);

        $post = new PostModel();
        $post->setBoard($board->getId());
        $post->setSubject($parsedBody['subject']);
        $post->setContent($parsedBody['content']);
        $post->setAuthor($parsedBody['author']);
        $post->setPassword($parsedBody['password']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $post->getId());
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
        return $this->entityManager->getRepository(PostModel::class)->findBy(['board' => $board->getId()], ['id' => 'DESC']);
    }
}

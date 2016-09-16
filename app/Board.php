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

    /**
     * @var BoardModel
     */
    private $board;

    public function __construct($name, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->board = $entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
    }

    public function index($name, ViewModel $viewModel)
    {
        $posts = $this->getPosts();

        $viewModel->set('board', $this->board);
        $viewModel->set('posts', $posts);

        return 'default/postList';
    }

    public function showPostingForm($name, ViewModel $viewModel)
    {
        $viewModel->set('board', $this->board);

        return 'default/postingForm';
    }

    public function write($name, $parsedBody)
    {
        $post = new PostModel();
        $post->setBoard($this->board->getId());
        $post->setSubject($parsedBody['subject']);
        $post->setContent($parsedBody['content']);
        $post->setAuthor($parsedBody['author']);
        $post->setPassword($parsedBody['password']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $post->getId());
    }

    /**
     * @return array
     */
    private function getPosts()
    {
        return $this->entityManager->getRepository(PostModel::class)
                ->findBy(['board' => $this->board->getId()], ['id' => 'DESC']);
    }
}

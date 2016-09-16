<?php

namespace App\Controller;

use Core\ViewModel;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManagerInterface;

class Post
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PostModel
     */
    private $post;

    /**
     * @var BoardModel
     */
    private $board;

    public function __construct($id, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->post = $this->entityManager->getRepository(PostModel::class)->find($id);
        $this->board = $this->entityManager->getRepository(BoardModel::class)->find($this->post->getBoard());
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        $viewModel->set('board', $this->board);

        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        $viewModel->set('board', $this->board);

        return 'default/postingForm';
    }
}

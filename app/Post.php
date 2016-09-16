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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index($id, ViewModel $viewModel)
    {
        $post = $this->entityManager->getRepository(PostModel::class)->find($id);

        $viewModel->set('post', $post);

        return 'default/post';
    }
}

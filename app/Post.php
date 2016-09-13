<?php

namespace App\Controller;

use Core\ViewModel;
use Core\DatabaseManager;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManager;

class Post
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = DatabaseManager::getEntityManager();
    }

    public function index($id, ViewModel $viewModel)
    {
        $post = $this->entityManager->getRepository(PostModel::class)->find($id);

        $viewModel->set('post', $post);

        return 'default/post';
    }
}

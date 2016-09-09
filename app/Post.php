<?php

namespace App\Controller;

use Core\ViewModel;
use Core\DatabaseManager;
use App\Auth\AuthManager;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManager;

class Post
{
    public function index($id, ViewModel $viewModel)
    {
        /** @var EntityManager */
        $entityManager = DatabaseManager::getInstance()->getEntityManager();
        $post = $entityManager->getRepository(PostModel::class)->find($id);

        $viewModel->set('post', $post);

        return 'default/post';
    }
}

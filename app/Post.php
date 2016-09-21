<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use \DateTime;

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
     * @var CommentModel
     */
    private $comment;

    public function __construct($id, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->post = $this->entityManager->getRepository(PostModel::class)->find($id);
        $this->comment = $this->entityManager->getRepository(CommentModel::class)->findBy(['post' => $this->post->getId()]);
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        $viewModel->set('comments', $this->comment);

        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);

        return 'default/postingForm';
    }

    public function edit($id, $parsedBody)
    {
        $this->post->setSubject($parsedBody['subject']);
        $this->post->setContent($parsedBody['content']);
        $this->post->setAuthor($parsedBody['author']);
        $this->post->setUpdatedAt(new DateTime());

        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }

    public function writeComment($id, $parsedBody, LoginManagerInterface $loginManager)
    {
        $comment = new CommentModel();
        $comment->setPost($this->post);
        $comment->setContent($parsedBody['content']);

        if ($loginManager->getLoggedAccountId()) {
            /** @var UserModel */
            $user = $this->entityManager->getRepository(UserModel::class)
                        ->findOneBy(['accountId' => $loginManager->getLoggedAccountId()]);

            $comment->setUser($user);
            $comment->setAuthor($user->getNickname());
            $comment->setPassword($user->getPassword());
        } else {
            $comment->setAuthor($parsedBody['author']);
            $comment->setPassword($parsedBody['password']);
        }

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }
}

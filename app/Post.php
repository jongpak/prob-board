<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Entity\Comment as CommentModel;
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
     * @var BoardModel
     */
    private $board;

    /**
     * @var CommentModel
     */
    private $comment;

    public function __construct($id, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->post = $this->entityManager->getRepository(PostModel::class)->find($id);
        $this->board = $this->entityManager->getRepository(BoardModel::class)->find($this->post->getBoard());
        $this->comment = $this->entityManager->getRepository(CommentModel::class)->findBy(['post' => $this->post->getId()]);
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        $viewModel->set('board', $this->board);
        $viewModel->set('comments', $this->comment);

        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        $viewModel->set('board', $this->board);

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

    public function writeComment($id, $parsedBody)
    {
        $comment = new CommentModel();
        $comment->setPost($this->post);
        $comment->setAuthor($parsedBody['author']);
        $comment->setPassword($parsedBody['password']);
        $comment->setContent($parsedBody['content']);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }
}

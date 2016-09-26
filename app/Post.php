<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Utils\FileDeleter;
use App\Utils\ContentUserInfoSetter;
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

    public function edit($id, $parsedBody, LoginManagerInterface $loginManager)
    {
        $this->post->setSubject($parsedBody['subject']);
        $this->post->setContent($parsedBody['content']);
        $this->post->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($this->post, $parsedBody, $loginManager);

        $this->entityManager->flush();

        FileDeleter::deleteFiles($this->getDeleteFileIdList($parsedBody));

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }

    public function writeComment($id, $parsedBody, LoginManagerInterface $loginManager)
    {
        $comment = new CommentModel();
        $comment->setPost($this->post);
        $comment->setContent($parsedBody['content']);
        ContentUserInfoSetter::fillUserInfo($comment, $parsedBody, $loginManager);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }

    private function getDeleteFileIdList($parsedBody)
    {
        $fileIdList = isset($parsedBody['delete-file'])
                        ? $parsedBody['delete-file']
                        : [];

        $deleteFileIdList = [];

        foreach ($fileIdList as $k => $v) {
            if ($v === 'on') {
                $deleteFileIdList[] = $k;
            }
        }

        return $deleteFileIdList;
    }
}

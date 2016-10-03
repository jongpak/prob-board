<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Utils\FileDeleter;
use App\Utils\FileUploader;
use App\Utils\FormUtility;
use App\Utils\ContentUserInfoSetter;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use \DateTime;
use Psr\Http\Message\ServerRequestInterface;

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

    public function __construct($id, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->post = $this->entityManager->getRepository(PostModel::class)->find($id);
    }

    public function index(ViewModel $viewModel)
    {
        $comments = $this->entityManager->getRepository(CommentModel::class)->findBy(['post' => $this->post->getId()]);
        $viewModel->set('post', $this->post);
        $viewModel->set('comments', $this->post->getComments());

        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        return 'default/postingForm';
    }

    public function edit($id, $parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $this->post->setSubject($parsedBody['subject']);
        $this->post->setContent($parsedBody['content']);
        $this->post->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($this->post, $parsedBody, $loginManager);

        $files = FileUploader::uploadFiles($req->getUploadedFiles()['file']);
        foreach ($files as $file) {
            $this->post->addAttachmentFile($file);
        }

        FileDeleter::deleteFiles(FormUtility::getCheckboxOnItem('delete-file', $parsedBody));

        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }

    public function showDeleteForm(ViewModel $viewModel)
    {
        $viewModel->set('post', $this->post);
        return 'default/delete';
    }

    public function delete()
    {
        $boardName = $this->post->getBoard()->getName();

        $this->post->setBoard(null);
        $this->entityManager->flush();

        return 'redirect:' . Application::getUrl($boardName);
    }

    public function writeComment($id, $parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
    {
        $comment = new CommentModel();
        $comment->setPost($this->post);
        $comment->setContent($parsedBody['content']);
        ContentUserInfoSetter::fillUserInfo($comment, $parsedBody, $loginManager);

        $files = FileUploader::uploadFiles($req->getUploadedFiles()['file']);
        foreach ($files as $file) {
            $comment->addAttachmentFile($file);
        }

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $this->post->getId());
    }
}

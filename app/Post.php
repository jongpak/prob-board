<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Post as PostModel;
use App\Entity\Comment as CommentModel;
use App\Utils\FileDeleter;
use App\Utils\FileUploader;
use App\Utils\FormUtility;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityFinder;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use \DateTime;
use \Exception;

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

    public function __construct($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $this->entityManager = $entityManager;
        $this->post = EntityFinder::findById(PostModel::class, $id);

        if ($this->post === null) {
            throw new Exception('Post is not found');
        }

        $viewModel->set('post', $this->post);
    }

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('comments', $this->post->getComments());
        return 'default/post';
    }

    public function showEditForm(ViewModel $viewModel)
    {
        return 'default/postingForm';
    }

    public function edit($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
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
        return 'default/delete';
    }

    public function delete()
    {
        $boardName = $this->post->getBoard()->getName();

        $this->post->setBoard(null);
        $this->entityManager->flush();

        return 'redirect:' . Application::getUrl($boardName);
    }

    public function writeComment($parsedBody, ServerRequestInterface $req, LoginManagerInterface $loginManager)
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

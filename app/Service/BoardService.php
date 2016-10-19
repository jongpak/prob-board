<?php

namespace App\Service;

use App\Entity\Board;
use App\Exception\EntityNotFound;
use Core\Utils\EntityFinder;
use Doctrine\ORM\EntityManagerInterface;

class BoardService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBoardEntity($name)
    {
        $board = EntityFinder::findOneBy(Board::class, ['name' => $name]);

        if ($board === null) {
            throw new EntityNotFound('Board is not found');
        }

        return $board;
    }
}
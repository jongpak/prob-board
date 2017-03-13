<?php

namespace App\Service;

use App\Entity\Board;
use App\Entity\Post;
use App\Exception\EntityNotFound;
use Core\Utils\EntityUtils\EntitySelect;
use Core\DatabaseManager;

class BoardService
{
    public function getBoardEntity($name)
    {
        $board = EntitySelect::select(Board::class)
            ->criteria(['name' => $name])
            ->findOne();

        if ($board === null) {
            throw new EntityNotFound('Board is not found');
        }

        return $board;
    }

    /**
     * @return array
     */
    public function getPosts(Board $board, $page = 1, $searchKeyword = null)
    {
        if($searchKeyword != null) {
            return $this->getPostsByKeyword($board, $page, $searchKeyword);
        }

        return EntitySelect::select(Post::class)
            ->criteria(['board' => $board->getId()])
            ->orderBy(['id' => 'DESC'])
            ->offsetStart($board->getListPerPage() * ($page - 1))
            ->offsetLength($board->getListPerPage())
            ->find();
    }

    private function getPostsByKeyword(Board $board, $page = 1, $searchKeyword)
    {
        $repository = DatabaseManager::getEntityManager()->getRepository(Post::class);
        $query = $repository->createQueryBuilder('p')
            ->where(
                '(
                    p.subject LIKE :keyword
                    OR
                    p.content LIKE :keyword
                    OR
                    p.author LIKE :keyword
                )'
            )
            ->andWhere('p.board = :board')
            ->setParameter('keyword', '%' . $searchKeyword . '%')
            ->setParameter('board', $board)
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($board->getListPerPage() * ($page - 1))
            ->setMaxResults($board->getListPerPage())
            ->getQuery();

            echo $query->getDql();

        return $query->getResult();
    }
}
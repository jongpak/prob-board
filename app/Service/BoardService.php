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
    public function getPosts(Board $board, $page = 1, $searchKeyword = null, $searchType = [])
    {
        if($searchKeyword != null && count($searchType) > 0) {
            return $this->getPostsByKeyword($board, $page, $searchKeyword, $searchType);
        }

        return EntitySelect::select(Post::class)
            ->criteria(['board' => $board->getId()])
            ->orderBy(['id' => 'DESC'])
            ->offsetStart($board->getListPerPage() * ($page - 1))
            ->offsetLength($board->getListPerPage())
            ->find();
    }

    private function getPostsByKeyword(Board $board, $page = 1, $searchKeyword, $searchType)
    {
        $queryBuilder = $this->getPostsQueryBuilderByKeyword($board, $page, $searchKeyword, $searchType);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function getPostsQueryBuilderByKeyword(Board $board, $page = 1, $searchKeyword, $searchType)
    {
        $repository = DatabaseManager::getEntityManager()->getRepository(Post::class);

        return $repository->createQueryBuilder('p')
            ->where(sprintf('(%s)', implode(' OR ', $this->getPostsWhereClauseByKeywordType($searchType))))
            ->andWhere('p.board = :board')
            ->setParameter('keyword', '%' . $searchKeyword . '%')
            ->setParameter('board', $board)
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($board->getListPerPage() * ($page - 1))
            ->setMaxResults($board->getListPerPage());
    }

    public function getPostsWhereClauseByKeywordType(array $searchType, $entityAlias = 'p', $keywordAlias = ':keyword')
    {
        $searchWhere = [];

        foreach($searchType as $key => $value) {
            if($value) {
                $searchWhere[] = sprintf('%s.%s LIKE %s', $entityAlias, $key, $keywordAlias);
            }
        }

        return $searchWhere;
    }
}
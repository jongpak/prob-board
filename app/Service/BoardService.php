<?php

namespace App\Service;

use App\Auth\LoginManagerInterface;
use App\Entity\Board;
use App\Entity\Post;
use App\Entity\User;
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
    public function getPosts(Board $board, $page = 1, $searchKeyword = null, $searchType = [], $targetAccountId = null)
    {
        if($searchKeyword != null && count($searchType) > 0) {
            return $this->getPostsByKeyword($board, $page, $searchKeyword, $searchType, $targetAccountId);
        }

        return EntitySelect::select(Post::class)
            ->criteria(['board' => $board->getId()])
            ->orderBy(['id' => 'DESC'])
            ->offsetStart($board->getListPerPage() * ($page - 1))
            ->offsetLength($board->getListPerPage())
            ->find();
    }

    private function getPostsByKeyword(Board $board, $page = 1, $searchKeyword, $searchType, $targetAccountId)
    {
        $queryBuilder = $this->getPostsQueryBuilderByKeyword($board, $page, $searchKeyword, $searchType, $targetAccountId);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function getPostsQueryBuilderByKeyword(Board $board, $page = 1, $searchKeyword, $searchType, $targetAccountId)
    {
        $repository = DatabaseManager::getEntityManager()->getRepository(Post::class);
        $targetAccount = EntitySelect::select(User::class)->criteria(['accountId' => $targetAccountId])->findOne();

        $queryBuilder = $repository->createQueryBuilder('p')
            ->where(sprintf('(%s)', implode(' OR ', $this->getPostsWhereClauseByKeywordType($searchType, 'p', ':keyword', ':user'))))
            ->andWhere('p.board = :board')
            ->setParameter('keyword', '%' . $searchKeyword . '%')
            ->setParameter('board', $board)
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($board->getListPerPage() * ($page - 1))
            ->setMaxResults($board->getListPerPage());

        if(array_key_exists('content', $searchType)) {
            $queryBuilder->setParameter('user', $targetAccount);
        }

        return $queryBuilder;
    }

    public function getPostsWhereClauseByKeywordType(array $searchType, $entityAlias = 'p', $keywordAlias = ':keyword', $targetAccountAlias = ':user')
    {
        $searchWhere = [];

        foreach($searchType as $key => $value) {
            switch($key) {
                case 'subject':
                case 'author':
                    $searchWhere[] = sprintf('(%s.%s LIKE %s)', $entityAlias, $key, $keywordAlias);
                    break;
                case 'content':
                    $searchWhere[] = sprintf('(%s.is_secret = true AND %s.%s LIKE %s AND %s.user = %s)', $entityAlias, $entityAlias, $key, $keywordAlias, $entityAlias, $targetAccountAlias);
                    $searchWhere[] = sprintf('(%s.is_secret = false AND %s.%s LIKE %s)', $entityAlias, $entityAlias, $key, $keywordAlias, $entityAlias, $targetAccountAlias);
            }
        }

        return $searchWhere;
    }
}
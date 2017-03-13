<?php

namespace App\Service;

use App\Entity\Board;
use App\Entity\Post;
use App\Exception\EntityNotFound;
use Core\Utils\EntityUtils\EntitySelect;

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
    public function getPosts(Board $board, $page = 1)
    {
        return EntitySelect::select(Post::class)
            ->criteria(['board' => $board->getId()])
            ->orderBy(['id' => 'DESC'])
            ->offsetStart($board->getListPerPage() * ($page - 1))
            ->offsetLength($board->getListPerPage())
            ->find();
    }
}
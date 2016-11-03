<?php

namespace App\Service;

use App\Entity\Board;
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
}
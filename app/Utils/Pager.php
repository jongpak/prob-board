<?php

namespace App\Utils;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Core\DatabaseManager;

class Pager
{
    private $currentPage = 1;
    private $listPerPage = 10;
    private $linkFactory = null;

    public function setCurrentPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }

    public function setListPerPage($num)
    {
        $this->listPerPage = $num;
        return $this;
    }

    public function setLinkFactoryFunction(callable $func)
    {
        $this->linkFactory = $func;
        return $this;
    }

    public function getPageNavigationByEntityModel($entityModel)
    {
        return $this->getPageNavigationByQueryBuilder(DatabaseManager::getEntityManager()->createQueryBuilder()
            ->select('e')
            ->from($entityModel, 'e')
        );
    }

    public function getPageNavigationByQueryBuilder($queryBuilder) {
        $pagerAdapter = new DoctrineORMAdapter($queryBuilder);

        return $this->render($pagerAdapter);
    }

    private function render($pagerAdapter) {
        $pager = new Pagerfanta($pagerAdapter);
        $pager->setMaxPerPage($this->listPerPage);
        $pager->setCurrentPage($this->currentPage);

        $pagerView = new TwitterBootstrap3View();
        return $pagerView->render($pager, $this->linkFactory);
    }
}

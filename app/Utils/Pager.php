<?php

namespace App\Utils;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Core\DatabaseManager;
use Doctrine\Common\Collections\Criteria;

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

    public function getPageNavigationByEntityModel($entityModel, $where = '')
    {
        $queryBuilder = DatabaseManager::getEntityManager()->createQueryBuilder()
            ->select('e')
            ->from($entityModel, 'e');

        if($where) {
            $queryBuilder->where($where);
        }

        return $this->getPageNavigationByQueryBuilder($queryBuilder);
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

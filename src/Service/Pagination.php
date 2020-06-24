<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

class Pagination
{
    private $entityClass;
    private $route;
    private $limit;
    private $criteria = [];
    private $order = ['id' => 'DESC'];
    private $currentPage;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route): void
    {
        $this->route = $route;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of manager
     */
    public function getManager() : EntityManagerInterface
    {
        return $this->em;
    }

    public function setManager($em)
    {
        $this->em = $em;

        return $this;
    }

    /**
     * Get paginated data
     * @return PaginatedRepresentation
     */
     public function getData(): PaginatedRepresentation
    {
        // Offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Get elements
        $repo = $this->em->getRepository($this->entityClass);
        $total = count($repo->findBy($this->criteria));
        $numberOfPages = ceil($total / $this->limit);
        $data = $repo->findBy($this->criteria, $this->order, $this->limit, $offset);

        $collection = new CollectionRepresentation($data);

        $paginated = new PaginatedRepresentation(
            $collection,
            $this->route,
            array(),
            $this->currentPage,
            $this->limit,
            $numberOfPages,
            'page',
            'limit',
            true,
            $total
        );

        return $paginated;
    }
}
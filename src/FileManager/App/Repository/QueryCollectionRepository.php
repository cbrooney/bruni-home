<?php

declare(strict_types=1);

namespace App\FileManager\App\Repository;

use App\FileManager\App\Entity\QueryCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class QueryCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QueryCollection::class);
    }

    /**
     * @throws Exception
     */
    public function getLatestByType(string $type): QueryCollection
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('q')
            ->from(QueryCollection::class, 'q')
            ->where($qb->expr()->eq('q.type', ':type'))
            ->setParameter('type', $type)
            ->orderBy('q.id', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}

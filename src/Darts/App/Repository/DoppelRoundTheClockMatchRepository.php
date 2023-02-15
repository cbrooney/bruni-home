<?php

declare(strict_types=1);

namespace App\Darts\App\Repository;

use App\Darts\App\Entity\DoppelRoundTheClockMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class DoppelRoundTheClockMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoppelRoundTheClockMatch::class);
    }

    public function store(DoppelRoundTheClockMatch $match)
    {
        $this->_em->persist($match);
        $this->_em->flush();
    }

    /**
     * @throws Exception
     */
    public function getMatchesPlayedUntilNow(): int
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(match.matchId)')
            ->from(DoppelRoundTheClockMatch::class, 'match');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }
}

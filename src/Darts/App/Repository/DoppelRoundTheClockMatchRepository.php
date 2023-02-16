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

    /**
     * @return array<int>
     */
    public function getAllMatchIds(): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('DISTINCT(match.matchId) AS matchId')
            ->from(DoppelRoundTheClockMatch::class, 'match');

        $result = $qb->getQuery()->getResult();

        $matchIds = [];

        foreach ($result as $matchIdEntry) {
            $matchIds[] = (int)$matchIdEntry['matchId'];
        }

        return $matchIds;
    }

    /**
     * @return array<DoppelRoundTheClockMatch>
     */
    public function getThrownDartsByMatch(int $matchId): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('match')
            ->from(DoppelRoundTheClockMatch::class, 'match')
            ->where('match.matchId = :matchId')
            ->setParameter('matchId', $matchId);

        return $qb->getQuery()->getResult();
    }
}

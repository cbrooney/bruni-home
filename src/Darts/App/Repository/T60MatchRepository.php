<?php

declare(strict_types=1);

namespace App\Darts\App\Repository;

use App\Darts\App\Entity\T60Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class T60MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, T60Match::class);
    }

    public function store(T60Match $match)
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
            ->from(T60Match::class, 'match');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return array<int>
     */
    public function getAllMatchIds(): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('DISTINCT(match.matchId) AS matchId')
            ->from(T60Match::class, 'match');

        $result = $qb->getQuery()->getResult();

        $matchIds = [];

        foreach ($result as $matchIdEntry) {
            $matchIds[] = (int)$matchIdEntry['matchId'];
        }

        return $matchIds;
    }

    /**
     * @return array<T60Match>
     */
    public function getThrownDartsByMatch(int $matchId): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('match')
            ->from(T60Match::class, 'match')
            ->where('match.matchId = :matchId')
            ->setParameter('matchId', $matchId);

        return $qb->getQuery()->getResult();
    }
}

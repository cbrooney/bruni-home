<?php

declare(strict_types=1);

namespace App\Darts\App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Darts\App\Repository\T60MatchRepository")
 * @ORM\Table(name="t_60_match")
 */
class T60Match
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="match_id", type="integer", nullable=false)
     */
    private int $matchId;

    /**
     * @ORM\Column(name="aufnahme", type="integer", nullable=false)
     */
    private int $aufnahme;

    /**
     * @ORM\Column(name="field_hit", type="integer", nullable=false)
     */
    private int $fieldHit;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    public function __construct(int $matchId, int $aufnahme, int $fieldHit)
    {
        $this->matchId = $matchId;
        $this->aufnahme = $aufnahme;
        $this->fieldHit = $fieldHit;

        $this->createdAt = new DateTime();
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }

    public function getAufnahme(): int
    {
        return $this->aufnahme;
    }

    public function getFieldHit(): int
    {
        return $this->fieldHit;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}

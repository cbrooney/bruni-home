<?php

declare(strict_types=1);

namespace App\Darts\App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Darts\App\Repository\T60MatchRepository")
 * @ORM\Table(name="doppel_round_the_clock_match")
 */
class DoppelRoundTheClockMatch
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
     * @ORM\Column(name="aufnahme_counter", type="integer", nullable=false)
     */
    private int $aufnahmeCounter;

    /**
     * @ORM\Column(name="doppel_ziel", type="integer", nullable=false)
     */
    private int $doppelZiel;

    /**
     * @ORM\Column(name="anzahl_getroffen", type="integer", nullable=false)
     */
    private int $anzahlGetroffen;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    public function __construct(int $matchId, int $aufnahmeCounter, int $doppelZiel, int $anzahlGetroffen)
    {
        $this->matchId = $matchId;
        $this->aufnahmeCounter = $aufnahmeCounter;
        $this->doppelZiel = $doppelZiel;
        $this->anzahlGetroffen = $anzahlGetroffen;

        $this->createdAt = new DateTime();
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }

    public function getAufnahmeCounter(): int
    {
        return $this->aufnahmeCounter;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDoppelZiel(): int
    {
        return $this->doppelZiel;
    }

    public function getAnzahlGetroffen(): int
    {
        return $this->anzahlGetroffen;
    }
}

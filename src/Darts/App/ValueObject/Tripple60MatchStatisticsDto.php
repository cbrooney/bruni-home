<?php

declare(strict_types=1);

namespace App\Darts\App\ValueObject;

use DateTime;

class Tripple60MatchStatisticsDto
{
    private const HEADER_VALUES = [
        'MatchId',
        'Aufnahmen',
    ];

    private int $matchId;
    private int $aufnahmen;
    private int $numberOfDarts;
    private DateTime $startTime;
    private DateTime $endTime;
    private int $points;
    private int $tripple20;
    private int $gerade;
    private int $links;
    private int $rechts;
    private int $punkte100Plus;
    private int $punkte140Plus;
    private int $punkte180;

    public function __construct(int $matchId)
    {
        $this->matchId = $matchId;
    }

    public function setAufnahmen(int $aufnahmen): self
    {
        $this->aufnahmen = $aufnahmen;

        return $this;
    }

    public function getAufnahmen(): int
    {
        return $this->aufnahmen;
    }

    public function toString(): string
    {
        return sprintf(
            "Aufnahmen:\t%d\nPunkte:\t\t%d\t\nNormalisiert:\t%f\n",
            $this->aufnahmen,
            $this->points,
            $this->normalizedPoints
        );
    }

    public function setStartTime(DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function setEndTime(DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function setNumberOfDarts(int $numberOfDarts): self
    {
        $this->numberOfDarts = $numberOfDarts;

        return $this;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function setPunkte180(int $punkte180): self
    {
        $this->punkte180 = $punkte180;
        return $this;
    }

    public function setTripple20(int $tripple20): self
    {
        $this->tripple20 = $tripple20;
        return $this;
    }

    public function setGerade(int $gerade): self
    {
        $this->gerade = $gerade;
        return $this;
    }

    public function setLinks(int $links): self
    {
        $this->links = $links;
        return $this;
    }

    public function setRechts(int $rechts): self
    {
        $this->rechts = $rechts;
        return $this;
    }

    public function setPunkte100Plus(int $punkte100Plus): self
    {
        $this->punkte100Plus = $punkte100Plus;
        return $this;
    }

    public function setPunkte140Plus(int $punkte140Plus): self
    {
        $this->punkte140Plus = $punkte140Plus;
        return $this;
    }

    public function getHeaderLine(): string
    {
        return implode(';', self::HEADER_VALUES);
    }
}

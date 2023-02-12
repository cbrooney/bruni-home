<?php

declare(strict_types=1);

namespace App\Darts\App\ValueObject;

use DateTime;

class Tripple60MatchStatisticsDto
{
    private const HEADER_VALUES = [
        'MatchId',
        'Aufnahmen',
        'Start',
        'Ende',
        'Dauer',
        '3D-Average',
        'T20',
        'T20 [%]',
        '100+',
        '140+',
        '180',
        'gerade [%]',
        'links [%]',
        'rechts [%]',
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

    /**
     * @return array
     */
    public function getValuesArray(): array
    {
        $dauer = $this->startTime->diff($this->endTime);

        return [
            $this->matchId,
            $this->aufnahmen,
            $this->startTime->format('Y-m-d H:i:s'),
            $this->endTime->format('Y-m-d H:i:s'),
            sprintf('%d:%d', $dauer->i, $dauer->s),
            round($this->points * 3 / $this->numberOfDarts, 2),
            $this->tripple20,
            $this->convertToPercentage($this->tripple20, $this->numberOfDarts),
            $this->punkte100Plus,
            $this->punkte140Plus,
            $this->punkte180,
            $this->convertToPercentage($this->gerade, $this->numberOfDarts),
            $this->convertToPercentage($this->links, $this->numberOfDarts),
            $this->convertToPercentage($this->rechts, $this->numberOfDarts),
        ];
    }

    public function getLine(): string
    {
        return implode(';', $this->getValuesArray());
    }

    public function getOutput(): string
    {
        $valuesArray = $this->getValuesArray();

        $output = '';
        foreach (self::HEADER_VALUES as $index => $headerValue) {
            $output .= $headerValue . ":\t" . $valuesArray[$index] . PHP_EOL;
        }

        return $output;
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }

    public function getNumberOfDarts(): int
    {
        return $this->numberOfDarts;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getTripple20(): int
    {
        return $this->tripple20;
    }

    public function getGerade(): int
    {
        return $this->gerade;
    }

    public function getLinks(): int
    {
        return $this->links;
    }

    public function getRechts(): int
    {
        return $this->rechts;
    }

    public function getPunkte100Plus(): int
    {
        return $this->punkte100Plus;
    }

    public function getPunkte140Plus(): int
    {
        return $this->punkte140Plus;
    }

    public function getPunkte180(): int
    {
        return $this->punkte180;
    }

    private function convertToPercentage(int $value, int $gesamtheit): float
    {
        return round($value * 100 / $gesamtheit, 2);
    }
}

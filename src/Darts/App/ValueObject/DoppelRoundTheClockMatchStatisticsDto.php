<?php

declare(strict_types=1);

namespace App\Darts\App\ValueObject;

use DateTime;

class DoppelRoundTheClockMatchStatisticsDto
{
    private const HEADER_VALUES = [
        'MatchId',
        'Start',
        'Ende',
        'Dauer',
        'Aufnahmen',
        'Doppeltreffer',
        'Doppelquote',
    ];

    private int $matchId;
    private int $aufnahmenCounter;
    private DateTime $startTime;
    private DateTime $endTime;
    private int $doppelTreffer;

    public function __construct(int $matchId)
    {
        $this->matchId = $matchId;
    }

    public function setAufnahmenCounter(int $aufnahmenCounter): self
    {
        $this->aufnahmenCounter = $aufnahmenCounter;

        return $this;
    }

    public function getAufnahmenCounter(): int
    {
        return $this->aufnahmenCounter;
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

    public function setDoppelTreffer(int $doppelTreffer): self
    {
        $this->doppelTreffer = $doppelTreffer;

        return $this;
    }

    public function getHeaderLine(): string
    {
        return implode(';', self::HEADER_VALUES);
    }

    /**
     * @return array<string>
     */
    public function getValuesArray(): array
    {
        $dauer = $this->startTime->diff($this->endTime);

        return [
            $this->matchId,
            $this->startTime->format('Y-m-d H:i:s'),
            $this->endTime->format('Y-m-d H:i:s'),
            sprintf('%d:%d', $dauer->i, $dauer->s),
            $this->aufnahmenCounter,
            $this->doppelTreffer,
            $this->convertToPercentage($this->doppelTreffer, $this->aufnahmenCounter * 3),
        ];
    }

    public function getLine(): string
    {
        return implode(';', $this->getValuesArray());
    }

    public function getOutput(): string
    {
        $valuesArray = $this->getValuesArray();

        $lengths = array_map('strlen', self::HEADER_VALUES);
        $maxLength = max($lengths);

        $output = '';
        foreach (self::HEADER_VALUES as $index => $headerValue) {
            $newLine = str_pad($headerValue, $maxLength) . " : " . $valuesArray[$index] . PHP_EOL;
            $output .= $newLine;
        }

        return $output;
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }

    public function getNumberOfDarts(): int
    {
        return $this->aufnahmenCounter * 3;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    private function convertToPercentage(int $value, int $gesamtheit): float
    {
        return round($value * 100 / $gesamtheit, 2);
    }
}

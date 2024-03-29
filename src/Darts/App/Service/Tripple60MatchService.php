<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use App\Darts\App\Entity\T60Match;
use App\Darts\App\Repository\T60MatchRepository;
use App\Darts\App\ValueObject\Tripple60MatchStatisticsDto;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class Tripple60MatchService implements DartMatchesInterface
{
    private const MAX_AUFNAHMEN = 40;

    private const POINTS_MAPPING = [
        0 => 0,
        1 => 5,
        2 => 20,
        3 => 1,
        4 => 15,
        5 => 60,
        6 => 3,
        7 => 5,
        8 => 20,
        9 => 1,
    ];

    private T60MatchRepository $t60MatchRepository;
    private Filesystem $filesystem;
    private LoggerInterface $logger;
    private string $statisticsDir;

    public function __construct(
        T60MatchRepository $t60MatchRepository,
        Filesystem $filesystem,
        LoggerInterface $logger,
        string $statisticsDir
    ) {
        $this->t60MatchRepository = $t60MatchRepository;
        $this->filesystem = $filesystem;
        $this->statisticsDir = $statisticsDir;
        $this->logger = $logger;
    }

    public function supports(string $type): bool
    {
        return $type === MatchSelectionService::MATCH_TRIPPLE_60;
    }

    public function recordNewMatch(
        int $nextMatchNumber,
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output
    ): int {
        for ($roundsCounter = 1; $roundsCounter < self::MAX_AUFNAHMEN + 1; $roundsCounter++) {
            $aufnahme = $this->getAufnahmeByUser($questionHelper, $input, $output, $roundsCounter);

            foreach ($aufnahme as $pfeil) {
                $t60Match = new T60Match($nextMatchNumber, $roundsCounter, (int)$pfeil);
                $this->t60MatchRepository->store($t60Match);
            }
        }

        return $nextMatchNumber;
    }

    public function createStatisticsFile(): string
    {
        $allMatchIds = $this->t60MatchRepository->getAllMatchIds();

        $statisticsDtos = [];

        foreach ($allMatchIds as $matchId) {
            $statisticsDtos[] = $this->createStatisticsDto($matchId);
        }

        return $this->createFile($statisticsDtos);
    }

    /**
     * @param array<Tripple60MatchStatisticsDto> $statisticsDtos
     */
    private function createFile(array $statisticsDtos): string
    {
        $now = new DateTime();

        $this->assertDirectoryExists($this->statisticsDir);

        $filename = $this->statisticsDir . 't60_stats_' . $now->format('Y-m-d_H-i-s') . '.csv';

        if (count($statisticsDtos) === 0) {
            return $filename;
        }

        $fileContent = $statisticsDtos[0]->getHeaderLine() . PHP_EOL;

        foreach ($statisticsDtos as $statisticsDto) {
            $fileContent .= $statisticsDto->getLine() . PHP_EOL;
        }

        file_put_contents($filename, $fileContent);

        $this->logger->info(sprintf('File %s created', $filename));

        return $filename;
    }

    private function createStatisticsDto(int $matchId): Tripple60MatchStatisticsDto
    {
        $dto = new Tripple60MatchStatisticsDto($matchId);

        $dartsForMatch = $this->t60MatchRepository->getThrownDartsByMatch($matchId);

        $numberOfDarts = count($dartsForMatch);

        if ($numberOfDarts === 0) {
            return $dto;
        }

        $points = 0;
        $tripple20 = 0;
        $gerade = 0;
        $links = 0;
        $rechts = 0;

        $punkteProAufnahme = [];

        $start = $dartsForMatch[0]->getCreatedAt();
        $ende = $dartsForMatch[array_key_last($dartsForMatch)]->getCreatedAt();

        foreach ($dartsForMatch as $dart) {
            $field = $dart->getFieldHit();

            if ($field === 5) {
                $tripple20++;
            }

            if (in_array($field, [2,5,8])) {
                $gerade++;
            }

            if (in_array($field, [1,4,7])) {
                $links++;
            }

            if (in_array($field, [3,6,9])) {
                $rechts++;
            }

            $punkteProPfeil = self::POINTS_MAPPING[$field];

            $points += $punkteProPfeil;

            if (!isset($punkteProAufnahme[$dart->getAufnahme()])) {
                $punkteProAufnahme[$dart->getAufnahme()] = 0;
            }

            $punkteProAufnahme[$dart->getAufnahme()] += $punkteProPfeil;
        }

        $aufnahmen = max(array_keys($punkteProAufnahme));

        $punkte100Plus = 0;
        $punkte140Plus = 0;
        $punkte180 = 0;

        foreach ($punkteProAufnahme as $punkte) {
            if ($punkte === 180) {
                $punkte180++;
                continue;
            }

            if ($punkte >= 140) {
                $punkte140Plus++;
                continue;
            }

            if ($punkte >= 100) {
                $punkte100Plus++;
            }
        }

        $dto->setAufnahmen($aufnahmen)
            ->setNumberOfDarts($numberOfDarts)
            ->setStartTime($start)
            ->setEndTime($ende)
            ->setPoints($points)
            ->setTripple20($tripple20)
            ->setGerade($gerade)
            ->setLinks($links)
            ->setRechts($rechts)
            ->setPunkte100Plus($punkte100Plus)
            ->setPunkte140Plus($punkte140Plus)
            ->setPunkte180($punkte180);

        return $dto;
    }

    public function getNextMatchNumber(): int
    {
        return $this->t60MatchRepository->getMatchesPlayedUntilNow() + 1;
    }

    public function wasLastMatchFinished(): bool
    {
        return true;
    }

    public function printStatisticsForMatch(int $matchId): void
    {
        $dto = $this->createStatisticsDto($matchId);

        echo $dto->getOutput() . PHP_EOL;
    }

    /**
     * @throws Exception
     * @return array<string>
     */
    private function getAufnahmeByUser(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output,
        int $roundsCounter
    ): array {
        $questionString = sprintf('Aufnahme <comment>%d</comment>: ...', $roundsCounter);
        $question = new Question($questionString, false);

        $arrayAufnahme = [];

        $question->setValidator(function ($aufnahmeString) {
            $arrayAufnahme = str_split($aufnahmeString);

            if (count($arrayAufnahme) !== 3) {
                throw new RuntimeException(
                    'Falsche Anzahl. Bitte erneut eingeben.'
                );
            }

            foreach ($arrayAufnahme as $pfeil) {
                if (!(is_numeric($pfeil) && (int)$pfeil >= 0 && (int)$pfeil <= 9)) {
                    throw new RuntimeException(
                        'Falsche Eingabe. Bitte erneut eingeben.'
                    );
                }
            }

            return $arrayAufnahme;
        });

        return $questionHelper->ask($input, $output, $question);
    }

    private function assertDirectoryExists(string $directory): void
    {
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory);
        }
    }
}

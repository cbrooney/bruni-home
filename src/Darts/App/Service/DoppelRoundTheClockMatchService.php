<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use App\Darts\App\Entity\DoppelRoundTheClockMatch;
use App\Darts\App\Entity\T60Match;
use App\Darts\App\Repository\DoppelRoundTheClockMatchRepository;
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

class DoppelRoundTheClockMatchService implements DartMatchesInterface
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

    private DoppelRoundTheClockMatchRepository $doppelRoundTheClockMatchRepository;
    private Filesystem $filesystem;
    private LoggerInterface $logger;
    private string $statisticsDir;

    public function __construct(
        DoppelRoundTheClockMatchRepository $doppelRoundTheClockMatchRepository,
        Filesystem $filesystem,
        LoggerInterface $logger,
        string $statisticsDir
    ) {
        $this->doppelRoundTheClockMatchRepository = $doppelRoundTheClockMatchRepository;
        $this->filesystem = $filesystem;
        $this->statisticsDir = $statisticsDir;
        $this->logger = $logger;
    }

    public function supports(string $type): bool
    {
        return $type === MatchSelectionService::MATCH_DOPPEL_ROUND_THE_CLOCK;
    }

    public function recordNewMatch(
        int $nextMatchNumber,
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output
    ): int {
        $doppelFelder = range(1, 5);

        $aufnahmeAufDoppelFeld = 0;
        $aufnahmeCounter = 0;

        foreach ($doppelFelder as $doppelFeld) {
//            $output->writeln(sprintf('%d. Wurf auf Doppel <comment>%d</comment>: ...', $aufnahmeAufDoppelFeld + 1, $doppelFeld));
            while ($aufnahmeAufDoppelFeld < 2) {
                $anzahlGetroffen = $this->getAufnahmeByUser($questionHelper, $input, $output, $aufnahmeAufDoppelFeld + 1, $doppelFeld);
                $aufnahmeCounter++;

                $matchEntry = new DoppelRoundTheClockMatch(
                    $nextMatchNumber,
                    $aufnahmeCounter,
                    $doppelFeld,
                    $anzahlGetroffen
                );

                $this->doppelRoundTheClockMatchRepository->store($matchEntry);

                $aufnahmeAufDoppelFeld++;
            }

            if ($aufnahmeAufDoppelFeld > 1) {
                $aufnahmeAufDoppelFeld = 0;
            }
        }



//        for ($roundsCounter = 1; $roundsCounter < self::MAX_AUFNAHMEN + 1; $roundsCounter++) {
//            $aufnahme = $this->getAufnahmeByUser($questionHelper, $input, $output, $roundsCounter);
//
//            foreach ($aufnahme as $pfeil) {
//                $t60Match = new T60Match($nextMatchNumber, $roundsCounter, (int)$pfeil);
//                $this->t60MatchRepository->store($t60Match);
//            }
//        }
//
//        $this->getStatisticsForMatch($nextMatchNumber);

        return $nextMatchNumber;
    }

    public function createStatisticsFile(): string
    {
//        $allMatchIds = $this->t60MatchRepository->getAllMatchIds();
        $allMatchIds = [];

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

//        $dartsForMatch = $this->t60MatchRepository->getThrownDartsByMatch($matchId);
        $dartsForMatch = 1;

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

        $dauer = $start->diff($ende);

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
            if ($punkte >= 100) {
                $punkte100Plus++;
            }

            if ($punkte >= 140) {
                $punkte140Plus++;
            }

            if ($punkte === 180) {
                $punkte180++;
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
        return $this->doppelRoundTheClockMatchRepository->getMatchesPlayedUntilNow() + 1;
    }

    public function wasLastMatchFinished(): bool
    {
        return true;
    }

    public function printStatisticsForMatch(int $matchId): void
    {
        echo 'Test' . PHP_EOL;
    }

    /**
     * @throws Exception
     */
    private function getAufnahmeByUser(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output,
        int $roundsCounter,
        int $doppelFeld
    ): int {
        $questionString = sprintf('%d. Wurf auf Doppel <comment>%d</comment>: ...', $roundsCounter, $doppelFeld);
//        $output->writeln(sprintf('%d. Wurf auf Doppel <comment>%d</comment>: ...', $aufnahmeAufDoppelFeld + 1, $doppelFeld));
        $question = new Question($questionString, false);

        $question->setValidator(function ($dartsThrownAnswer) {
            if (!(is_numeric($dartsThrownAnswer) && (int)$dartsThrownAnswer >= 0 && (int)$dartsThrownAnswer <= 3)) {
                throw new RuntimeException(
                    'Falsche Eingabe. Bitte erneut eingeben.'
                );
            }

            return (int)$dartsThrownAnswer;
        });

        $points = $questionHelper->ask($input, $output, $question);

        return (int)$points;
    }

    private function assertDirectoryExists(string $directory): void
    {
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory);
        }
    }
}

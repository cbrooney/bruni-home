<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use App\Darts\App\Entity\DoppelRoundTheClockMatch;
use App\Darts\App\Entity\T60Match;
use App\Darts\App\Repository\DoppelRoundTheClockMatchRepository;
use App\Darts\App\Repository\T60MatchRepository;
use App\Darts\App\ValueObject\DoppelRoundTheClockMatchStatisticsDto;
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
        $doppelFelder = range(1, 20);

        $aufnahmeAufDoppelFeld = 0;
        $aufnahmeCounter = 0;

        foreach ($doppelFelder as $doppelFeld) {
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

        return $nextMatchNumber;
    }

    public function createStatisticsFile(): string
    {
        $allMatchIds = $this->doppelRoundTheClockMatchRepository->getAllMatchIds();

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

    private function createStatisticsDto(int $matchId): DoppelRoundTheClockMatchStatisticsDto
    {
        $dto = new DoppelRoundTheClockMatchStatisticsDto($matchId);

        $alleAufnahmen = $this->doppelRoundTheClockMatchRepository->getThrownDartsByMatch($matchId);

        $aufnahmenCounter = count($alleAufnahmen);

        if ($aufnahmenCounter === 0) {
            return $dto;
        }

        $doppelTreffer = 0;

        $start = $alleAufnahmen[0]->getCreatedAt();
        $ende = $alleAufnahmen[array_key_last($alleAufnahmen)]->getCreatedAt();

        foreach ($alleAufnahmen as $aufnahme) {
            $doppelTreffer += $aufnahme->getAnzahlGetroffen();
        }

        $dto
            ->setAufnahmenCounter($aufnahmenCounter)
            ->setStartTime($start)
            ->setEndTime($ende)
            ->setDoppelTreffer($doppelTreffer);

        return $dto;
    }

    public function getNextMatchNumber(): int
    {
        return $this->doppelRoundTheClockMatchRepository->getMatchesPlayedUntilNow() + 1;
    }

    public function printStatisticsForMatch(int $matchId): void
    {
        $dto = $this->createStatisticsDto($matchId);

        echo $dto->getOutput() . PHP_EOL;
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

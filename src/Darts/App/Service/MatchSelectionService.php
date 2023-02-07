<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use App\Darts\App\Entity\T60Match;
use App\Darts\App\Repository\T60MatchRepository;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MatchSelectionService implements MatchSelector
{
    public const MATCH_TRIPPLE_60 = 't-60';

    /**
     * @var iterable<DartMatchesInterface>
     */
    private $dartMatchesTypes;

    /**
     * @param iterable<DartMatchesInterface> $dartMatchesTypes
     */
    public function __construct(iterable $dartMatchesTypes)
    {
        $this->dartMatchesTypes = $dartMatchesTypes;
    }

    public function startNewMatch(string $type): void
    {
        foreach ($this->dartMatchesTypes as $dartMatchesType) {
            if ($dartMatchesType->supports($type)) {
                var_dump($type);
                var_dump(get_class($dartMatchesType));
            }
        }
    }

//    public function recordNewMatch(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int
//    {
//        $nextMatchNumber = $this->getNextMatchNumber();
//
//        $output->writeln(sprintf('Spiel Nummer %d', $nextMatchNumber));
//
//        for ($roundsCounter = 1; $roundsCounter < 51; $roundsCounter++) {
//            $aufnahme = $this->getAufnahmeByUser($questionHelper, $input, $output);
//
//            foreach ($aufnahme as $pfeil) {
//                $t60Match = new T60Match($nextMatchNumber, $roundsCounter, (int)$pfeil);
//                $this->t60MatchRepository->store($t60Match);
//            }
//        }
//
//        return $nextMatchNumber;
//    }
//
//    public function getNextMatchNumber(): int
//    {
//        return $this->t60MatchRepository->getMatchesPlayedUntilNow() + 1;
//    }
//
//    public function wasLastMatchFinished(): bool
//    {
//        return true;
//    }
//
//    public function getStatisticsForMatch(int $matchId): void
//    {
//        $dartsForMatch = $this->t60MatchRepository->getThrownDartsByMatch($matchId);
//
//        $numberOfDarts = count($dartsForMatch);
//
//        if ($numberOfDarts === 0) {
//            return;
//        }
//
//        $points = 0;
//        $t60 = 0;
//        $gerade = 0;
//        $links = 0;
//        $rechts = 0;
//
//        $punkteProAufnahme = [];
//
//        $start = $dartsForMatch[0]->getCreatedAt();
//        $ende = $dartsForMatch[array_key_last($dartsForMatch)]->getCreatedAt();
//
//        $dauer = $start->diff($ende);
//
//        foreach ($dartsForMatch as $dart) {
//            $field = $dart->getFieldHit();
//
//            if ($field === 5) {
//                $t60++;
//            }
//
//            if (in_array($field, [2,5,8])) {
//                $gerade++;
//            }
//
//            if (in_array($field, [1,4,7])) {
//                $links++;
//            }
//
//            if (in_array($field, [3,6,9])) {
//                $rechts++;
//            }
//
//            $punkteProPfeil = self::POINTS_MAPPING[$field];
//
//            $points += $punkteProPfeil;
//
//            if (!isset($punkteProAufnahme[$dart->getAufnahme()])) {
//                $punkteProAufnahme[$dart->getAufnahme()] = 0;
//            }
//
//            $punkteProAufnahme[$dart->getAufnahme()] += $punkteProPfeil;
//        }
//
//        $aufnahmen = max(array_keys($punkteProAufnahme));
//
//        $punkte100Plus = 0;
//        $punkte140Plus = 0;
//        $punkte180 = 0;
//
//        foreach ($punkteProAufnahme as $punkte) {
//            if ($punkte >= 100) {
//                $punkte100Plus++;
//            }
//
//            if ($punkte >= 140) {
//                $punkte140Plus++;
//            }
//
//            if ($punkte === 180) {
//                $punkte180++;
//            }
//        }
//
//        echo "Aufnahmen: " . $aufnahmen . PHP_EOL;
//        echo "Start:\t" . $start->format('Y-m-d H:i:s') . PHP_EOL;
//        echo "Dauer:\t" . $dauer->i . ':' . $dauer->s . ' min' . PHP_EOL;
//        echo '3D-Avg: ' . round($points * 3 / $numberOfDarts, 2) . PHP_EOL;
//        echo "T20:\t" . round($t60 * 100 / $numberOfDarts, 2) . '%' . PHP_EOL;
//        echo "Gerade:\t" . round($gerade * 100 / $numberOfDarts, 2) . '%' . PHP_EOL;
//        echo "Links:\t" . round($links * 100 / $numberOfDarts, 2) . '%' . PHP_EOL;
//        echo "Rechts:\t" . round($rechts * 100 / $numberOfDarts, 2) . '%' . PHP_EOL;
//        echo "100+:\t" . $punkte100Plus . PHP_EOL;
//        echo "140+:\t" . $punkte140Plus . PHP_EOL;
//        echo "180:\t" . $punkte180 . PHP_EOL;
//    }
//
//    /**
//     * @throws Exception
//     * @return array<string>
//     */
//    private function getAufnahmeByUser(
//        QuestionHelper $questionHelper,
//        InputInterface $input,
//        OutputInterface $output
//    ): array {
//        $questionString = 'Aufnahme:';
//        $question = new Question($questionString, false);
//
//        $arrayAufnahme = [];
//
//        $question->setValidator(function ($aufnahmeString) {
//            $arrayAufnahme = str_split($aufnahmeString);
//
//            if (count($arrayAufnahme) !== 3) {
//                throw new RuntimeException(
//                    'Falsche Anzahl. Bitte erneut eingeben.'
//                );
//            }
//
//            foreach ($arrayAufnahme as $pfeil) {
//                if (!(is_numeric($pfeil) && (int)$pfeil >= 0 && (int)$pfeil <= 9)) {
//                    throw new RuntimeException(
//                        'Falsche Eingabe. Bitte erneut eingeben.'
//                    );
//                }
//            }
//
//            return $arrayAufnahme;
//        });
//
//        $arrayAufnahme = $questionHelper->ask($input, $output, $question);
//
//        return $arrayAufnahme;
//    }
}

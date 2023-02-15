<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchSelectionService implements MatchSelector
{
    public const MATCH_TRIPPLE_60 = 't-60';
    public const MATCH_DOPPEL_ROUND_THE_CLOCK = 'doppel-rtc';

    public const ALLOWED_ARGUMENTS_FOR_MATCH_TYPES = [
        MatchSelectionService::MATCH_TRIPPLE_60,
        MatchSelectionService::MATCH_DOPPEL_ROUND_THE_CLOCK,
    ];

    /**
     * @var iterable<DartMatchesInterface>
     */
    private iterable $dartMatchesTypes;

    /**
     * @param iterable<DartMatchesInterface> $dartMatchesTypes
     */
    public function __construct(iterable $dartMatchesTypes)
    {
        $this->dartMatchesTypes = $dartMatchesTypes;
    }

    public function startNewMatch(
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output,
        string $type
    ): void {
        foreach ($this->dartMatchesTypes as $dartMatchesType) {
            if ($dartMatchesType->supports($type)) {
//                var_dump($type);
//                var_dump(get_class($dartMatchesType));
                $matchNumber = $dartMatchesType->getNextMatchNumber();

                $output->writeln(
                    sprintf('Starte Spiel Nummer %d', $matchNumber)
                );

                $dartMatchesType->recordNewMatch(
                    $matchNumber,
                    $helper,
                    $input,
                    $output
                );

                $dartMatchesType->printStatisticsForMatch($matchNumber);

                return;
            }
        }
    }

    public function createStatisticsFile(string $type): void
    {
        foreach ($this->dartMatchesTypes as $dartMatchesType) {
            if ($dartMatchesType->supports($type)) {
                $dartMatchesType->createStatisticsFile();
                return;
            }
        }
    }
}

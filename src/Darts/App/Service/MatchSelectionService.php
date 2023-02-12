<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchSelectionService implements MatchSelector
{
    public const MATCH_TRIPPLE_60 = 't-60';

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
                $dartMatchesType->recordNewMatch(
                    $helper,
                    $input,
                    $output
                );

                return;
            }
        }
    }

    public function createStatisticsFile(
        InputInterface $input,
        string $type
    ): void {
        foreach ($this->dartMatchesTypes as $dartMatchesType) {
            if ($dartMatchesType->supports($type)) {
                $dartMatchesType->createStatisticsFile();
            }
        }
    }
}

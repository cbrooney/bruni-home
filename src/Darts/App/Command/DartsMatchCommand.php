<?php

declare(strict_types=1);

namespace App\Darts\App\Command;

//use App\Darts\App\Service\BrunisRoundTheClockMatchService;
//use App\Darts\App\Service\FiveHundredOneMatchService;
//use App\Darts\App\Service\OneSeventyMatchService;
//use App\Darts\App\Service\SinglesRoundMatchService;
//use App\Darts\App\Service\Tripple60MatchService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class DartsMatchCommand extends Command
{
    protected static $defaultName = 'start:darts:match';

    private const MATCH_SINGLE_ROUND = 'Single Round';
    private const MATCH_ONE_SEVENTY = '170';
    private const MATCH_FIVE_HUNDRED_ONE = '501';
    private const MATCH_ROUND_THE_CLOCK_BRUNI = 'Bruni Round the Clock';
    private const MATCH_TRIPPLE_60 = 't-60';

    private const MATCH_CHOICES = [
        self::MATCH_SINGLE_ROUND,
        self::MATCH_ONE_SEVENTY,
        self::MATCH_FIVE_HUNDRED_ONE,
        self::MATCH_ROUND_THE_CLOCK_BRUNI,
        self::MATCH_TRIPPLE_60,
    ];

    private const ALLOWED_OPTIONS = [
        self::MATCH_TRIPPLE_60,
    ];

//    private $singlesRoundMatchService;
//    private $oneSevenetyMatchService;
//    private $fiveHundredOneMatchService;
//    private $brunisRoundTheClockMatchService;
//    private $tripple60MatchService;
    private LoggerInterface $logger;
    private string $kernelDir;

    public function __construct(
//        SinglesRoundMatchService $singlesRoundMatchService,
//        OneSeventyMatchService $oneSevenetyMatchService,
//        FiveHundredOneMatchService $fiveHundredOneMatchService,
//        BrunisRoundTheClockMatchService $brunisRoundTheClockMatchService,
//        Tripple60MatchService $tripple60MatchService
        LoggerInterface $logger,
        string $kernelDir
    ) {
        parent::__construct();

//        $this->singlesRoundMatchService = $singlesRoundMatchService;
//        $this->oneSevenetyMatchService = $oneSevenetyMatchService;
//        $this->fiveHundredOneMatchService = $fiveHundredOneMatchService;
//        $this->brunisRoundTheClockMatchService = $brunisRoundTheClockMatchService;
//        $this->tripple60MatchService = $tripple60MatchService;
        $this->logger = $logger;
        $this->kernelDir = $kernelDir;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'match-type',
                InputOption::VALUE_REQUIRED,
                sprintf('the match type. Allowed types: %s', implode(',', self::ALLOWED_OPTIONS))
            )
            ->setDescription('start darts match by argument');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('start');

        var_dump($this->kernelDir);

        // echo $this->singlesRoundMatchService->getStatisticsForMatch(1)->toString();

//        $helper = $this->getHelper('question');
//        $question = new ChoiceQuestion(
//            'Welches Spiel möchtest du spielen?',
//            self::MATCH_CHOICES
//        );
//
//        $selection = $helper->ask($input, $output, $question);
//
//        switch ($selection) {
//            case self::MATCH_SINGLE_ROUND:
//                $matchNumber = $this->singlesRoundMatchService->recordNewMatch($helper, $input, $output);
//                echo $this->singlesRoundMatchService->getStatisticsForMatch($matchNumber)->toString();
//                break;
//            case self::MATCH_ONE_SEVENTY:
//                $dartsThrown = $this->oneSevenetyMatchService->recordNewMatch($helper, $input, $output);
//                $output->writeln('Benötigte Darts: ' . $dartsThrown);
//                break;
//            case self::MATCH_FIVE_HUNDRED_ONE:
//                $dartsThrown = $this->fiveHundredOneMatchService->recordNewMatch($helper, $input, $output);
//                $output->writeln('Benötigte Darts: ' . $dartsThrown);
//                $output->writeln('Three Dart Average: ' . (string)(501 * 3 / $dartsThrown));
//                break;
//            case self::MATCH_ROUND_THE_CLOCK_BRUNI:
//                $matchNumber = $this->brunisRoundTheClockMatchService->recordNewMatch($helper, $input, $output);
//                break;
//            case self::MATCH_TRIPPLE_60:
//                $matchNumber = $this->tripple60MatchService->recordNewMatch($helper, $input, $output);
//                $this->tripple60MatchService->getStatisticsForMatch($matchNumber);
//                for ($i=1;$i<8;$i++) {
//                    //$this->tripple60MatchService->getStatisticsForMatch($i);
//                    //echo PHP_EOL;
//                }
//                break;
//        }

        $this->logger->info('end');

        return self::SUCCESS;
    }
}

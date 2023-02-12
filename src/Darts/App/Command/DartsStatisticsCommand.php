<?php

declare(strict_types=1);

namespace App\Darts\App\Command;

use App\Darts\App\Service\MatchSelectionService;
use App\Darts\App\Service\MatchSelector;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DartsStatisticsCommand extends Command
{
    protected static $defaultName = 'darts:match:statistics';

    public const ALLOWED_ARGUMENTS_FOR_MATCH_TYPES = [
        MatchSelectionService::MATCH_TRIPPLE_60,
    ];

    private MatchSelector $matchSelector;
    private LoggerInterface $logger;

    public function __construct(
        MatchSelector $matchSelector,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->matchSelector = $matchSelector;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'match-type',
                InputOption::VALUE_REQUIRED,
                sprintf('the match type. Allowed types: %s', implode(',', self::ALLOWED_ARGUMENTS_FOR_MATCH_TYPES))
            )
            ->setDescription('start darts match by argument');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Start');

        try {
            $matchType = $this->getTypeArgument($input);

            $this->matchSelector->createStatisticsFile($matchType);
        } catch (Throwable $exception) {
            $this->logger->error(sprintf('Error while running Command. Error was: %s', $exception->getMessage()));

            return self::FAILURE;
        }

        $this->logger->info('End');

        return self::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function getTypeArgument(InputInterface $input): string
    {
        $inputType = $input->getArgument('match-type');

        if (!in_array($inputType, self::ALLOWED_ARGUMENTS_FOR_MATCH_TYPES)) {
            throw new Exception(sprintf('Match type %s not allowed', $inputType));
        }

        return $inputType;
    }
}

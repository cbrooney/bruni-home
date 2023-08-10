<?php

declare(strict_types=1);

namespace App\FileManager\App\Command;

use App\FileManager\App\Service\FileScanningService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class FileAnalysisCommand extends Command
{
    protected static $defaultName = 'file:analysis';

    private LoggerInterface $logger;
    private FileScanningService $fileScanningService;

    public function __construct(
        FileScanningService $fileScanningService,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->fileScanningService = $fileScanningService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'directory',
                InputOption::VALUE_REQUIRED,
                'The root directory to scan:'
            )
            ->setDescription('collect recursively information of data in directory');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Start');

        try {
            $directory = $input->getArgument('directory');
            var_dump($directory);

            $this->fileScanningService->scanDirectory($directory);
        } catch (Throwable $exception) {
            $this->logger->error(sprintf('Error while running Command. Error was: %s', $exception->getMessage()));

            return self::FAILURE;
        }

        $this->logger->info('End');

        return self::SUCCESS;
    }
}

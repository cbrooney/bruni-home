<?php

declare(strict_types=1);

namespace App\FileManager\App\Command;

use App\FileManager\App\Service\FileHashingService;
use App\FileManager\App\Service\FileScanningService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class FileHashingCommand extends Command
{
    protected static $defaultName = 'file:hashing';

    private LoggerInterface $logger;
    private FileHashingService $fileHashingService;

    public function __construct(
        FileHashingService $fileHashingService,
        LoggerInterface    $logger
    ) {
        parent::__construct();
        $this->fileHashingService = $fileHashingService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Start');

        try {
            $this->fileHashingService->hashFiles(null);
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
    private function getRootDirectory(InputInterface $input): string
    {
        $directory = rtrim($input->getArgument('directory'), '/');

        if (!is_dir($directory)) {
            throw new Exception(sprintf('%s is not a directory or does not exist', $directory));
        }

        return $directory;
    }
}

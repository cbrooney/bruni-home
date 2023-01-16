<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\EntityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCommand extends Command
{
    protected static $defaultName = 'entity:save';

    private LoggerInterface $logger;
    private EntityService $entityService;

    public function __construct(
        EntityService $entityService,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->entityService = $entityService;
    }

    protected function configure(): void
    {
        $this->setDescription('Test');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Start');

        $this->entityService->saveNewEntity();

        $this->logger->info('End');
        return self::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace App\FileManager\App\Service;

use App\FileManager\App\Repository\FileListEntityRepository;
use Exception;
use Psr\Log\LoggerInterface;

class FileHashingService
{
    private FileListEntityRepository $fileListEntityRepository;
    private LoggerInterface $logger;

    public function __construct(
        FileListEntityRepository $fileListEntityRepository,
        LoggerInterface $logger
    ) {
        $this->fileListEntityRepository = $fileListEntityRepository;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function hashFiles(?int $run): void
    {
        $counter = 0;
        $previousCounter = 0;

        while (true) {
            $entriesToHash = $this->fileListEntityRepository->getEntriesWithoutHash(20);

            if (count($entriesToHash) === 0) {
                $this->logger->info('All done!');
                break;
            }

            foreach ($entriesToHash as $fileToHash) {
                $fileToHash->setHash();
                $counter = $counter + 1;
            }

            $this->fileListEntityRepository->flush();
            $this->fileListEntityRepository->clear();

            $this->logger->info(sprintf('%d hashes calculated', $counter));

            if ($previousCounter === $counter) {
                $this->logger->error('BREAK: got twice same counts');
                break;
            }

            $previousCounter = $counter;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\FileManager\App\Service;

use App\FileManager\App\Repository\FileListEntityRepository;
use Container5xIgght\getMaker_PhpCompatUtilService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;

class FileScanningService
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
    public function scanDirectory(string $rootDir): void
    {
        $allFiles = [];
        $allFiles = $this->getAllFilesFromFolder($rootDir, $allFiles);

        $this->logger->info(sprintf('Found %d files', count($allFiles)));
        
        $this->saveFileListFromArray($allFiles, $rootDir);
    }

    public function getAllFilesFromFolder(string $folder, array $allFilesInFolder): ?array
    {
        $folderInhalt = scandir($folder);

        $folderInhalt = array_diff($folderInhalt, array('..', '.'));

        foreach ($folderInhalt as $file) {
            $fullPath = $folder . '/' . $file;
            if (!is_dir($fullPath)) {
                $allFilesInFolder[] = $fullPath;
            }

            if (is_dir($fullPath)) {
                $allFilesInFolder = $this->getAllFilesFromFolder($fullPath, $allFilesInFolder);
            }
        }

        return $allFilesInFolder;
    }

    /**
     * @throws Exception
     */
    public function saveFileListFromArray(array $allFiles, string $rootDir): void
    {
        $run = $this->fileListEntityRepository->getNewRunNumber();

        $batches = array_chunk($allFiles, 50);
        $counter = 0;

        $stopWatch = new Stopwatch();

        foreach ($batches as $batch) {
            foreach ($batch as $file) {
                try {
                    $fileListEntity = $this->fileListEntityRepository->createFileListEntity(
                        $file,
                        $run,
                        $rootDir,
                        $stopWatch
                    );
                    $this->fileListEntityRepository->persist($fileListEntity);
                    $counter = $counter + 1;
                } catch (Throwable $exception) {
                    $this->logger->error(
                        sprintf('Error for file: %s with message: %s', $file, $exception->getMessage())
                    );
                    continue;
                }
            }

            $stopWatch->start('flush');
            $this->fileListEntityRepository->flush();
            $this->fileListEntityRepository->clear();
            $stopWatch->stop('flush');

            $duration = $stopWatch->getEvent('SplFileInfo')->getDuration();
            echo sprintf('SplFileInfo: %d', (int)($duration / 1000)) . PHP_EOL;

            $duration = $stopWatch->getEvent('DateTimeCreation')->getDuration();
            echo sprintf('DateTimeCreation: %d', (int)($duration / 1000)) . PHP_EOL;

            $duration = $stopWatch->getEvent('Entity')->getDuration();
            echo sprintf('Entity: %d', (int)($duration / 1000)) . PHP_EOL;

            $duration = $stopWatch->getEvent('flush')->getDuration();
            echo sprintf('flush: %d', (int)($duration / 1000)) . PHP_EOL;

            $this->logger->info(sprintf('Saved %d entries', $counter));
        }
    }
}

<?php

declare(strict_types=1);

namespace App\FileManager\App\Service;

use App\FileManager\App\Repository\FileListEntityRepository;
use Container5xIgght\getMaker_PhpCompatUtilService;
use Exception;
use Psr\Log\LoggerInterface;
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

        $batches = array_chunk($allFiles, 100);
        $counter = 0;

        foreach ($batches as $batch) {
            foreach ($batch as $file) {
                try {
                    $fileListEntity = $this->fileListEntityRepository->createFileListEntity($file, $run, $rootDir);
                    $this->fileListEntityRepository->persist($fileListEntity);
                    $counter = $counter + 1;
                } catch (Throwable $exception) {
                    $this->logger->error(sprintf('Error for file: %s with message: %s', $file, $exception->getMessage()));
                    continue;
                }
            }

            $this->fileListEntityRepository->flush();
            $this->fileListEntityRepository->clear();

            $this->logger->info(sprintf('Saved %d entries', $counter));
        }
    }
}

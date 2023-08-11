<?php

declare(strict_types=1);

namespace App\FileManager\App\Service;

use App\FileManager\App\Repository\FileListEntityRepository;
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

    public function scanDirectory(string $rootDir): void
    {

        $allFiles = [];
        $allFiles = $this->getAllFilesFromFolder($rootDir, $allFiles);

        var_dump($allFiles);

        $this->saveFileListFromArray($allFiles);
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
    public function saveFileListFromArray(array $allFiles): void
    {
        $run = $this->fileListEntityRepository->getNewRunNumber();

        foreach ($allFiles as $file) {
            try {
                $fileListEntity = $this->fileListEntityRepository->createFileListEntity($file, $run);
                $this->fileListEntityRepository->persist($fileListEntity);
            } catch (Throwable $exception) {
                $this->logger->error(sprintf('Error for file: %s with message: %s', $file, $exception->getMessage()));
                continue;
            }
        }

        $this->fileListEntityRepository->flush();
    }
}

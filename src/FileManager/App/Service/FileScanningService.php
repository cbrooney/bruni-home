<?php

declare(strict_types=1);

namespace App\FileManager\App\Service;

use App\FileManager\App\Repository\FileListEntityRepository;

class FileScanningService
{
    private FileListEntityRepository $fileListEntityRepository;

    public function __construct(
        FileListEntityRepository $fileListEntityRepository
    ) {
        $this->fileListEntityRepository = $fileListEntityRepository;
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

    public function saveFileListFromArray(array $allFiles): void
    {
        foreach ($allFiles as $file) {
            $fileListEntity = $this->fileListEntityRepository->createFileListEntity($file);
            $this->fileListEntityRepository->persist($fileListEntity);
        }

        $this->fileListEntityRepository->flush();
    }
}

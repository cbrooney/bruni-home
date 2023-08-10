<?php

declare(strict_types=1);

namespace App\FileManager\App\Repository;

use App\FileManager\App\Entity\FileListEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use SplFileInfo;

class FileListEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileListEntity::class);
    }

    public function createFileListEntity(string $fullPath): FileListEntity
    {
        $fileListEntity = new FileListEntity($fullPath);

        $splFileInfo = new SplFileInfo($fullPath);

        $fileListEntity
            ->setFileSize($splFileInfo->getSize())
            ->setFileName($splFileInfo->getFilename())
            ->setFileType($splFileInfo->getExtension())
            ->setHash(sha1_file($fullPath))
            ->setRelativePath('/');

        return $fileListEntity;
    }

    /**
     * @throws Exception
     */
    public function persist(FileListEntity $fileListEntity): void
    {
        $this->_em->persist($fileListEntity);
    }

    /**
     * @throws Exception
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\FileManager\App\Repository;

use App\FileManager\App\Entity\FileListEntity;
use DateTime;
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

    public function createFileListEntity(string $fullPath, int $run, string $rootDir): FileListEntity
    {
        $fileListEntity = new FileListEntity($fullPath, $run);

        $splFileInfo = new SplFileInfo($fullPath);

        $relativePath = str_replace($rootDir, '', $splFileInfo->getPath());

        $mtime = DateTime::createFromFormat('U', (string)$splFileInfo->getMTime());
        $ctime = DateTime::createFromFormat('U', (string)$splFileInfo->getCTime());
        $atime = DateTime::createFromFormat('U', (string)$splFileInfo->getATime());

        $fileListEntity
            ->setFileSize($splFileInfo->getSize())
            ->setFileName($splFileInfo->getFilename())
            ->setFileType($splFileInfo->getExtension())
            ->setHash(sha1_file($fullPath))
            ->setRelativePath($relativePath)
            ->setMTime($mtime)
            ->setATime($atime)
            ->setCTime($ctime);

        return $fileListEntity;
    }

    /**
     * @throws Exception
     */
    public function getNewRunNumber(): int
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(file.run)')
            ->from(FileListEntity::class, 'file');

        return (int)$qb->getQuery()->getSingleScalarResult() + 1;
    }


    /**
     * @return array<FileListEntity>
     * @throws Exception
     */
    public function getFiguresToShow(): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('file')
            ->from(FileListEntity::class, 'file')
            ->where($qb->expr()->in('file.fileType', ':fileTypes'))
            ->setParameter(
                'fileTypes',
                [
                    'JPG',
                    'jpg',
                ]
            )
        ;

        return $qb->getQuery()->getResult();
        return array_column($qb->getQuery()->getResult(), 'fileName');
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

<?php

declare(strict_types=1);

namespace App\FileManager\App\Repository;

use App\FileManager\App\Entity\FileListEntity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use SplFileInfo;
use Symfony\Component\Stopwatch\Stopwatch;

class FileListEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileListEntity::class);
    }

    public function createFileListEntity(string $fullPath, int $run, string $rootDir, Stopwatch $stopwatch): FileListEntity
    {
        $fileListEntity = new FileListEntity($fullPath, $run);

        $stopwatch->start('SplFileInfo');
        $splFileInfo = new SplFileInfo($fullPath);
        //usleep(100000);
        $stopwatch->stop('SplFileInfo');

        $relativePath = str_replace($rootDir, '', $splFileInfo->getPath());

        $stopwatch->start('DateTimeCreation');
        //usleep(200000);
        $mtime = DateTime::createFromFormat('U', (string)$splFileInfo->getMTime());
        $ctime = DateTime::createFromFormat('U', (string)$splFileInfo->getCTime());
        $atime = DateTime::createFromFormat('U', (string)$splFileInfo->getATime());
        $stopwatch->stop('DateTimeCreation');

        $stopwatch->start('Entity');
        //usleep(300000);
        $fileListEntity
            ->setFileSize($splFileInfo->getSize())
            ->setFileName($splFileInfo->getFilename())
            ->setFileType($splFileInfo->getExtension())
            ->setHash(sha1_file($fullPath))
            ->setRelativePath($relativePath)
            ->setMTime($mtime)
            ->setATime($atime)
            ->setCTime($ctime);
        $stopwatch->stop('Entity');

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
    }

    /**
     * @return FileListEntity>
     * @throws Exception
     */
    public function getByFullPath(string $fullPath): ?FileListEntity
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('file')
            ->from(FileListEntity::class, 'file')
            ->where($qb->expr()->eq('file.fullPath', ':fullPath'))
            ->setParameter('fullPath', $fullPath)
            ->orderBy('file.run', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
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

    public function clear(): void
    {
        $this->_em->clear();
        $this->_em->getConnection()->close();
        $this->_em->getConnection()->connect();
    }
}

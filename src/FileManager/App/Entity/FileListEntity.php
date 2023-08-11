<?php

declare(strict_types=1);

namespace App\FileManager\App\Entity;

use App\FileManager\App\Repository\FileListEntityRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileListEntityRepository::class)
 * @ORM\Table(
 *     name="file_list"
 * )
 */
class FileListEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="run", type="integer", nullable=false)
     */
    private int $run;

    /**
     * @ORM\Column(name="full_path", type="string", nullable=false, length=300)
     */
    private string $fullPath;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=false, length=100)
     */
    private string $fileName;

    /**
     * @ORM\Column(name="relative_path", type="string", nullable=false, length=200)
     */
    private string $relativePath;

    /**
     * @ORM\Column(name="file_type", type="string", nullable=true, length=20)
     */
    private ?string $fileType = null;

    /**
     * @ORM\Column(name="file_size", type="integer", nullable=true)
     */
    private ?int $fileSize = null;

    /**
     * @ORM\Column(name="hash", type="string", nullable=true, length=300)
     */
    private ?string $hash = null;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTimeInterface $createdAt;

    public function __construct(string $fullPath, int $run)
    {
        $this->fullPath = $fullPath;
        $this->run = $run;

        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function setFullPath(string $fullPath): FileListEntity
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): FileListEntity
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function setRelativePath(string $relativePath): FileListEntity
    {
        $this->relativePath = $relativePath;
        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(?string $fileType): FileListEntity
    {
        $this->fileType = $fileType;
        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): FileListEntity
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): FileListEntity
    {
        $this->hash = $hash;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}

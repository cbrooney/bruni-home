<?php

declare(strict_types=1);

namespace App\FileManager\App\Entity;

use App\FileManager\App\Repository\QueryCollectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QueryCollectionRepository::class)
 * @ORM\Table(
 *     name="query_collection"
 * )
 */
class QueryCollection
{
    public const TYPE_PICTURE = 'picture';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="type", type="string", nullable=false, length=20)
     */
    private string $type = '';

    /**
     * @ORM\Column(name="active", type="smallint", nullable=false)
     */
    private int $active = 0;

    /**
     * @ORM\Column(name="query", type="text", nullable=false)
     */
    private string $query;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): QueryCollection
    {
        $this->type = $type;
        return $this;
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function setActive(int $active): QueryCollection
    {
        $this->active = $active;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query): QueryCollection
    {
        $this->query = $query;
        return $this;
    }
}

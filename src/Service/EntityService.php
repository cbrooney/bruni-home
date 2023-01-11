<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TestEntity;
use App\Repository\TestEntityRepository;

class EntityService
{
    private TestEntityRepository $testEntityRepository;

    public function __construct(
        TestEntityRepository $testEntityRepository
    ) {
        $this->testEntityRepository = $testEntityRepository;
    }

    public function saveNewEntity(): void
    {
        $entity = new TestEntity();

        $this->testEntityRepository->add($entity);

        var_dump($entity);
    }
}

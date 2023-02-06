<?php

declare(strict_types=1);

namespace App\FirstTest\App\Service;

use App\FirstTest\App\Entity\TestEntity;
use App\FirstTest\App\Repository\TestEntityRepository;

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

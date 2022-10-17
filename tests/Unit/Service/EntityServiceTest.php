<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TestEntity;
use App\Repository\TestEntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\PhpUnit\ProphecyTrait;

class EntityServiceTest extends TestCase
{
    use ProphecyTrait;

    /** @var TestEntityRepository|ObjectProphecy */
    private $testEntityRepository;
    private EntityService $subject;

    public function setUp(): void
    {
        $this->testEntityRepository = $this->prophesize(TestEntityRepository::class);

        $this->subject = new EntityService($this->testEntityRepository->reveal());
    }

    public function testSuccesful(): void
    {
        $entity = new TestEntity();

        $this->testEntityRepository
            ->add(Argument::any())
            ->shouldBeCalled();

        $this->subject->saveNewEntity();
    }
}

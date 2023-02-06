<?php

declare(strict_types=1);

namespace App\FirstTest\App\Service;

use App\FirstTest\App\Entity\TestEntity;
use App\FirstTest\App\Repository\TestEntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

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
        var_dump('Run unit test');
        $entity = new TestEntity();

        $this->testEntityRepository
            ->add(Argument::any())
            ->shouldBeCalled();

        $this->subject->saveNewEntity();
    }
}

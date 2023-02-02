<?php

declare(strict_types=1);

namespace App\Command;

use App\DatabaseTestCase;
use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCommandTest extends DatabaseTestCase
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->fixturesDir = __DIR__ . '/Fixtures/';

        parent::setUp();
    }

    public function testSomething(): void
    {
        var_dump('Run functional test');

        $commandName = 'entity:save';
        $arguments = [
            '--env' => self::$kernel->getEnvironment(),
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];
        $values = $this->executeWithCommandTester($commandName, $arguments, $options);

        $this->assertEquals(0, $values['exitCode']);

        $tableEntries = $this->connection
            ->executeQuery('SELECT * FROM `test_table`')
            ->fetchAllAssociative();

        $this->assertCount(3, $tableEntries);
    }
}

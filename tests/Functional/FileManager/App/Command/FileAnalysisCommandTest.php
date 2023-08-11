<?php

declare(strict_types=1);

namespace App\FileManager\App\Command;

use App\DatabaseTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class FileAnalysisCommandTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $this->fixturesDir = __DIR__ . '/Fixtures/';

        parent::setUp();
    }

    public function testSomething(): void
    {
        $commandName = 'file:analysis';
        $arguments = [
            'directory' => $this->fixturesDir . 'RootDirectory/',
            '--env' => self::$kernel->getEnvironment(),
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];
        $values = $this->executeWithCommandTester($commandName, $arguments, $options);

        $this->assertEquals(0, $values['exitCode']);

        $tableEntries = $this->connection
            ->executeQuery('SELECT * FROM `file_list`')
            ->fetchAllAssociative();

        $this->assertCount(6, $tableEntries);
    }
}

<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class EntityCommandTest extends WebTestCase
{
    protected ?string $fixturesDir;
    protected ?string $fixturesFile;

    protected EntityManagerInterface $entityManager;
    protected Connection $connection;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser, @see https://symfony.com/doc/4.4/testing.html#write-your-first-application-test
        // calling bootKernel and creating the client in a test is deprecated as of 4.3, @see FrameworkBundle/Test/WebTestCase.php:45
        // self::$client = self::createClient(['environment' => 'test', 'debug' => false]);

        if (!static::$kernel instanceof KernelInterface) {
            static::bootKernel(['environment' => 'test', 'debug' => true]);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;

        $this->connection = $entityManager->getConnection();

        $this->fixturesFile = null;
        $this->fixturesDir = __DIR__ . '/Fixtures/';

        $this->recreateDatabase();
        $this->initializeFixtures();
        $this->loadSqlFixtures();
    }

    protected function tearDown(): void
    {
        return;
    }

    /**
     * @throws Exception
     */
    private function recreateDatabase(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $connection = $entityManager->getConnection();

//        $database = $this->getTestDatabaseName();
        $database = 'home_test';

        $this->validateDatabaseSuffix($database);
        $connection->executeStatement("DROP DATABASE IF EXISTS `" . $database . "`;");
        $connection->executeStatement("CREATE DATABASE IF NOT EXISTS`" . $database . "`");
    }

    protected function executeWithCommandTester(string $commandName, array $arguments = [], array $commandTestOptions = []): array
    {
        $application = new Application(self::$kernel);
        $command = $application->find($commandName);
        $commandTester = new CommandTester($command);
        $commandTester->execute($arguments, $commandTestOptions);

        return [
            'output' => $commandTester->getDisplay(),
            'exitCode' => $commandTester->getStatusCode(),
        ];
    }

    /**
     * Kill running test to prevent dropping a database without suffix "_test"
     */
    private function validateDatabaseSuffix(string $database): void
    {
        if (false === strpos($database, '_test')) {
            die('Test database name must be suffixed with "_test"');
        }
    }

    /**
     * @throws Exception
     */
    private function getTestDatabaseName(): string
    {
        return static::getContainer()->getParameter('test_database');
    }

    /**
     * @throws Exception
     */
    private function initializeFixtures(): void
    {
//        $connection = $this->getConnectionRegistry()->getConnection('auxmoney');
//        foreach ($this->getRequiredTables() as $tableGroup) {
//            $connection->exec(file_get_contents(__DIR__ . '/DatabaseFixtures/' . $tableGroup));
//        }
    }

    /**
     * @throws Exception
     */
    public function loadSqlFixtures(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        // in case of SQL fixture files, insert directly
        if ($this->fixturesFile !== null && preg_match('/\.sql$/', $this->fixturesFile)) {
            if (!file_exists($this->fixturesFile)) {
                throw new Exception('File does not exist: ' . $this->fixturesFile);
            }
            $entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=0;');
            $entityManager->getConnection()->executeStatement(file_get_contents($this->fixturesFile));
            $entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if ($this->fixturesDir === null) {
            return;
        }

        if (!file_exists($this->fixturesDir)) {
            throw new Exception('Fixtures dir does not exist: ' . $this->fixturesDir);
        }

        $entityManager->getConnection()->executeStatement('use home_test;');

        $entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=0;');

        $finder = new Finder();
        foreach ($finder->in($this->fixturesDir)->name('*.sql')->files() as $file) {
            $entityManager->getConnection()->executeStatement(file_get_contents($file->getRealPath()));
        }

        $entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function testSomething(): void
    {
        $commandName = 'entity:save';
        $arguments = [
            '--env' => self::$kernel->getEnvironment(),
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];
        $values = $this->executeWithCommandTester($commandName, $arguments, $options);

        $this->assertEquals(0, $values['exitCode']);

        $tableEntries = $this->connection->executeQuery('SELECT * FROM `test_table`')->fetchAllAssociative();

        $this->assertCount(3, $tableEntries);
    }
}

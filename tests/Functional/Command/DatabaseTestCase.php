<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Throwable;

class DatabaseTestCase extends WebTestCase
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
//        $this->recreateSchema();
        $this->createTables();
        $this->loadSqlFixtures();
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $database = $this->getTestDatabaseName();
        $this->validateDatabaseSuffix($database);
        $this->connection->executeStatement("DROP DATABASE IF EXISTS `" . $database . "`;");

        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    private function recreateDatabase(): void
    {
        $database = $this->getTestDatabaseName();
        // this is needed locally if the database container is already running
        // maria db is somehow still problematic
        try {
            $process = new Process(['/var/www/html/bin/console', 'doctrine:database:create']);
            $process->run();

            if (!$process->isSuccessful()) {
                echo 'Running "doctrine:database:create" was not successful' . PHP_EOL;
            }
        } catch (Throwable $exception) {
            echo 'Problem while running "doctrine:database:create":' . $exception->getMessage() . PHP_EOL;
        }

//        try {
//            $result = $this->connection->executeQuery("SHOW DATABASES;")->fetchAllAssociative();
//            var_dump($result);
//        } catch (Throwable $exception) {
//            var_dump($exception->getMessage());
//        }

        $this->validateDatabaseSuffix($database);

        $this->connection->executeStatement("DROP DATABASE IF EXISTS `" . $database . "`;");
        $this->connection->executeStatement("CREATE DATABASE IF NOT EXISTS`" . $database . "`");
    }

    private function recreateSchema(): void
    {
        $meta = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($meta)) {
            $tool = new SchemaTool($this->entityManager);
            $tool->dropSchema($meta);
            try {
                $tool->createSchema($meta);
            } catch (ToolsException $e) {
                throw new InvalidArgumentException(
                    "Database schema is not buildable: {$e->getMessage()}",
                    $e->getCode(),
                    $e
                );
            }
        }
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
        return $this->connection->getParams()['dbname'];
    }

    /**
     * @throws Exception
     */
    private function createTables(): void
    {
        $database = $this->getTestDatabaseName();
        $this->connection->executeStatement(sprintf('use %s;', $database));
        $this->connection->executeStatement(file_get_contents(__DIR__ . '/../tables.sql'));
    }

    /**
     * @throws Exception
     */
    public function loadSqlFixtures(): void
    {
        if ($this->fixturesFile !== null && preg_match('/\.sql$/', $this->fixturesFile)) {
            if (!file_exists($this->fixturesFile)) {
                throw new Exception('File does not exist: ' . $this->fixturesFile);
            }
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');
            $this->connection->executeStatement(file_get_contents($this->fixturesFile));
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if ($this->fixturesDir === null) {
            return;
        }

        if (!file_exists($this->fixturesDir)) {
            throw new Exception('Fixtures dir does not exist: ' . $this->fixturesDir);
        }

        $database = $this->getTestDatabaseName();
        $this->connection->executeStatement(sprintf('use %s;', $database));
        $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');

        $finder = new Finder();
        foreach ($finder->in($this->fixturesDir)->name('*.sql')->files() as $file) {
            $this->connection->executeStatement(file_get_contents($file->getRealPath()));
        }

        $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

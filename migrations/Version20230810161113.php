<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810161113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `file_list` (
              `id` int NOT NULL AUTO_INCREMENT,
              `run` int NOT NULL,
              `full_path` varchar(300) NOT NULL,
              `file_name` varchar(100) NOT NULL,
              `relative_path` varchar(200) NOT NULL,
              `file_type` varchar(20) DEFAULT NULL,
              `personen` varchar(300) DEFAULT NULL,
              `comment` varchar(300) DEFAULT NULL,
              `file_size` int DEFAULT NULL,
              `hash` varchar(300) DEFAULT NULL,
              `m_time` datetime DEFAULT NULL,
              `a_time` datetime DEFAULT NULL,
              `c_time` datetime DEFAULT NULL,
              `created_at` datetime NOT NULL,
              PRIMARY KEY (`id`),
              KEY `run_index` (`run`),
              KEY `full_path_index` (`full_path`),
              KEY `m_time_index` (`m_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

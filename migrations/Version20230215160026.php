<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215160026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'doppel-r-t-c';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `doppel_round_the_clock_match` (
              `id` int NOT NULL AUTO_INCREMENT,
              `match_id` int NOT NULL,
              `aufnahme_counter` int NOT NULL,
              `doppel_ziel` int NOT NULL,
              `anzahl_getroffen` int NOT NULL,
              `created_at` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}

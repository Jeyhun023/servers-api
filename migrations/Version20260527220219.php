<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260527220219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create servers table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE servers (
            id INT AUTO_INCREMENT NOT NULL, 
            model VARCHAR(255) NOT NULL, 
            ram VARCHAR(32) NOT NULL, 
            hdd VARCHAR(32) NOT NULL, 
            location VARCHAR(64) NOT NULL, 
            price VARCHAR(16) NOT NULL, 
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE servers');
    }
}

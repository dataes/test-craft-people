<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126141118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fos_user ADD deck_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479111948DC ON fos_user (deck_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479111948DC');
        $this->addSql('DROP INDEX UNIQ_957A6479111948DC ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP deck_id');
    }
}

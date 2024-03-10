<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308143711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649d17f50a6');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN uuid TO public_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B5B48B91 ON "user" (public_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_8D93D649B5B48B91');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN public_id TO uuid');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649d17f50a6 ON "user" (uuid)');
    }
}

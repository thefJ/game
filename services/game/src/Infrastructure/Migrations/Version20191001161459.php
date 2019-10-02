<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191001161459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE league ADD transliterated_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EB4C3184A430BA1 ON league (transliterated_name)');
        $this->addSql('CREATE INDEX sport_transliterated_name_idx ON league USING gist (transliterated_name gist_trgm_ops)');
        $this->addSql('CREATE INDEX league_transliterated_name_idx ON sport USING gist (transliterated_name gist_trgm_ops)');
        $this->addSql('ALTER TABLE team ADD transliterated_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F4A430BA1 ON team (transliterated_name)');
        $this->addSql('CREATE INDEX team_transliterated_name_idx ON team USING gist (transliterated_name gist_trgm_ops)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX UNIQ_3EB4C3184A430BA1');
        $this->addSql('DROP INDEX sport_transliterated_name_idx');
        $this->addSql('ALTER TABLE league DROP transliterated_name');
        $this->addSql('DROP INDEX UNIQ_C4E0A61F4A430BA1');
        $this->addSql('DROP INDEX team_transliterated_name_idx');
        $this->addSql('ALTER TABLE team DROP transliterated_name');
        $this->addSql('DROP INDEX league_transliterated_name_idx');
    }
}

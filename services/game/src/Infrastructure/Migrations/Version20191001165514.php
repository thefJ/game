<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191001165514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE league ADD sport_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN league.sport_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3EB4C318AC78BCF8 ON league (sport_id)');
        $this->addSql('ALTER TABLE team ADD sport_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN team.sport_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61FAC78BCF8 ON team (sport_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FAC78BCF8');
        $this->addSql('DROP INDEX IDX_C4E0A61FAC78BCF8');
        $this->addSql('ALTER TABLE team DROP sport_id');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C318AC78BCF8');
        $this->addSql('DROP INDEX IDX_3EB4C318AC78BCF8');
        $this->addSql('ALTER TABLE league DROP sport_id');
    }
}

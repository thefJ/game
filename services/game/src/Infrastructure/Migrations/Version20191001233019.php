<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191001233019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c296cd8ae');
        $this->addSql('DROP INDEX idx_232b318c296cd8ae');
        $this->addSql('ALTER TABLE game ADD guest_team_id UUID NOT NULL');
        $this->addSql('ALTER TABLE game RENAME COLUMN team_id TO host_team_id');
        $this->addSql('COMMENT ON COLUMN game.guest_team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1E90F49F FOREIGN KEY (host_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C69A91CE2 FOREIGN KEY (guest_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318C1E90F49F ON game (host_team_id)');
        $this->addSql('CREATE INDEX IDX_232B318C69A91CE2 ON game (guest_team_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C1E90F49F');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C69A91CE2');
        $this->addSql('DROP INDEX IDX_232B318C1E90F49F');
        $this->addSql('DROP INDEX IDX_232B318C69A91CE2');
        $this->addSql('ALTER TABLE game ADD team_id UUID NOT NULL');
        $this->addSql('ALTER TABLE game DROP host_team_id');
        $this->addSql('ALTER TABLE game DROP guest_team_id');
        $this->addSql('COMMENT ON COLUMN game.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c296cd8ae FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_232b318c296cd8ae ON game (team_id)');
    }
}

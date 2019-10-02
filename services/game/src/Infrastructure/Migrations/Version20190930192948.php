<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190930192948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE league (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN league.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE game_buffer (hash VARCHAR(255) NOT NULL, game_id UUID DEFAULT NULL, sport VARCHAR(255) NOT NULL, league VARCHAR(255) NOT NULL, host_team VARCHAR(255) NOT NULL, guest_team VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, language VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, PRIMARY KEY(hash))');
        $this->addSql('CREATE INDEX IDX_4C6F5D3AE48FD905 ON game_buffer (game_id)');
        $this->addSql('COMMENT ON COLUMN game_buffer.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sport (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sport.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE team (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN team.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, sport_id UUID NOT NULL, league_id UUID NOT NULL, team_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, language VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318CAC78BCF8 ON game (sport_id)');
        $this->addSql('CREATE INDEX IDX_232B318C58AFC4DE ON game (league_id)');
        $this->addSql('CREATE INDEX IDX_232B318C296CD8AE ON game (team_id)');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.sport_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.league_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE game_buffer ADD CONSTRAINT FK_4C6F5D3AE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C58AFC4DE');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CAC78BCF8');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C296CD8AE');
        $this->addSql('ALTER TABLE game_buffer DROP CONSTRAINT FK_4C6F5D3AE48FD905');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE game_buffer');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE game');
    }
}

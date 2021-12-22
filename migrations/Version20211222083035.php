<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222083035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1A85EFD2A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sport AS SELECT id, user_id, name FROM sport');
        $this->addSql('DROP TABLE sport');
        $this->addSql('CREATE TABLE sport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_1A85EFD2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sport (id, user_id, name) SELECT id, user_id, name FROM __temp__sport');
        $this->addSql('DROP TABLE __temp__sport');
        $this->addSql('CREATE INDEX IDX_1A85EFD2A76ED395 ON sport (user_id)');
        $this->addSql('DROP INDEX UNIQ_5F37A13BA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__token AS SELECT id, user_id, created_at, value FROM token');
        $this->addSql('DROP TABLE token');
        $this->addSql('CREATE TABLE token (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , value VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO token (id, user_id, created_at, value) SELECT id, user_id, created_at, value FROM __temp__token');
        $this->addSql('DROP TABLE __temp__token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13BA76ED395 ON token (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64941DEE7B9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, token_id, username, email, phone, age, created_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, token_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, phone VARCHAR(255) DEFAULT NULL COLLATE BINARY, age INTEGER DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_8D93D64941DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, token_id, username, email, phone, age, created_at) SELECT id, token_id, username, email, phone, age, created_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64941DEE7B9 ON user (token_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1A85EFD2A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sport AS SELECT id, user_id, name FROM sport');
        $this->addSql('DROP TABLE sport');
        $this->addSql('CREATE TABLE sport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO sport (id, user_id, name) SELECT id, user_id, name FROM __temp__sport');
        $this->addSql('DROP TABLE __temp__sport');
        $this->addSql('CREATE INDEX IDX_1A85EFD2A76ED395 ON sport (user_id)');
        $this->addSql('DROP INDEX UNIQ_5F37A13BA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__token AS SELECT id, user_id, created_at, value FROM token');
        $this->addSql('DROP TABLE token');
        $this->addSql('CREATE TABLE token (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , value VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO token (id, user_id, created_at, value) SELECT id, user_id, created_at, value FROM __temp__token');
        $this->addSql('DROP TABLE __temp__token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13BA76ED395 ON token (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64941DEE7B9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, token_id, username, email, phone, age, created_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, token_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, age INTEGER DEFAULT NULL, created_at DATETIME DEFAULT \'NULL --(DC2Type:datetime_immutable)\' --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO user (id, token_id, username, email, phone, age, created_at) SELECT id, token_id, username, email, phone, age, created_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64941DEE7B9 ON user (token_id)');
    }
}
